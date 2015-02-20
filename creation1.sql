create database if not exists asteras_vouliagmenis default character set utf8 collate utf8_unicode_ci;
use asteras_vouliagmenis;

create table if not exists hotel (
  hotelID int(11) not null auto_increment,
  hotelName varchar(30) not null,
  hotelStreet varchar(30) not null,
  hotelPostCode int(11) not null,
  hotelCity varchar(30) not null,
  hotelRanking enum('1 star','2 stars','3 stars','4 stars','5 stars') not null,
  hotelConstructionDate date not null,
  hotelRenoDate date,
  hotelPool enum('no','yes'),
  hotelParking enum('no','yes'),
  hotelGym enum('no','yes'),
  primary key (hotelID)
);

create table if not exists hotelTelephone (
  hotelID int(11) not null,
  hoteltelephone int(11) not null,
  primary key (hotelID,hoteltelephone),
  foreign key (hotelID) references hotel (hotelID) on update cascade on delete cascade
);

create table if not exists hotelExtraService (
  hotelID int(11) not null,
  Service varchar(30) not null,
  primary key (hotelID,Service),
  foreign key (hotelID) references hotel (hotelID) on update cascade on delete cascade
);

create table if not exists room (
  hotelid int(11) not null,
  roomnumber int(11) not null,
  roomtype enum('1 bed','2 beds','3 beds','4 beds','5 beds','6 beds'),
  price int(11) not null,
  Internet enum('no','yes'),
  TV enum('no','yes'),
  Aircondition enum('no','yes'),
  Fridge enum('no','yes'),
  Phone enum('no','yes'),
  primary key (hotelid,roomnumber),
  key(hotelid),
  key(roomnumber),
  foreign key (hotelID) references hotel (hotelID) on update cascade on delete cascade
);

create table if not exists employee (
  employeeID int(11) not null auto_increment,
  employeeAFM int(11) not null,
  employeeFirstName varchar(30) not null,
  employeeLastName varchar(30) not null,
  employeeGender enum('male','female'),
  employeeStreet varchar(30),
  employeeStreetNumber int(11),
  employeePostCode int(11),
  employeeCity varchar(30),
  hotelID int(11) not null,
  employeeStartDate date not null,
  employeeEndDate date not null,
  employeeWage int(11) not null,
  employeeNumberOfChildren int (11),
  primary key (employeeID),
  foreign key (hotelID) references hotel (hotelID) on update cascade on delete cascade
);

create table if not exists employeetelephone (
  employeeID int(11) not null,
  employeeTelephone int(11) not null,
  primary key (employeeID,employeeTelephone),
  foreign key (employeeID) references employee (employeeID) on update cascade on delete cascade
);

create table if not exists chef (
  chefID int(11) not null,
  chefSpecialization varchar(30) not null,
  primary key (chefID),
  foreign key (chefID) references employee (employeeID) on update cascade on delete cascade
) ;

create table if not exists driver (
  driverID int(11) not null,
  driverLicence varchar(30) not null,
  driverCarModel varchar(30),
  primary key (driverID),
  foreign key (driverID) references employee (employeeID) on update cascade on delete cascade
) ;

create table if not exists customer (
  customerID int(11) not null auto_increment,
  customerIDNumber int(11) not null,
  customerFirstName varchar(30) not null,
  customerLastName varchar(30) not null,
  customerStreetName varchar(30),
  customerPostalCode int(11),
  customerCity varchar(30),
  customerCreditCardNumber int(11) not null,
  primary key (customerID)
);

create or replace view customer_important_info as
select customerFirstName,customerLastName,customerCreditCardNumber,customerIDNumber
from customer;

create table if not exists customertelephone (
  customerID int(11) not null,
  customerPhoneNumber int(11) not null,
  primary key (customerID,customerPhoneNumber),
  foreign key (customerID) references customer (customerID) on update cascade on delete cascade
);

create table if not exists reservation (
  reservationID int(11) not null auto_increment,
  reservationAgreementDate date not null,
  reservationStartDate date not null,
  reservationEndDate date not null,
  reservationPaymentMethod enum('CreditCard','BankAccount','Physical','Other') not null,
  customerID int(11) not null,
  hotelID int(11) not null,
  roomNumber int(11) not null,
  reservationCost int(11),
  primary key (reservationID),
  foreign key (hotelID) references room (hotelID) on update cascade on delete cascade,
  foreign key (roomnumber) references room (roomnumber) on update cascade on delete cascade,
  foreign key (customerID) references customer (customerID) on update cascade on delete cascade
);

create or replace view reservation_detailed as
select  c.customerFirstName , c.customerLastName , h.hotelName , res.roomNumber ,res.reservationStartDate ,res.reservationEndDate,res.reservationCost
from reservation as res, customer as c, hotel as h
where res.customerID=c.customerID and res.hotelID=h.hotelID;

create table if not exists vip (
  customerID int(11) not null,
  VIPOccupation varchar(30) not null,
  primary key (customerID),
  foreign key (customerID) references customer(customerID) on update cascade on delete cascade
);

create table if not exists vippreference (
  customerID int(11) not null,
  preference varchar(30) not null,
  primary key (customerID,preference), 
  foreign key (customerID) references vip(customerID) on update cascade on delete cascade
);
  
  
create table if not exists vip_chef (
  customerID int(11) not null,
  employeeID int(11) not null,
  primary key (customerID,employeeID),
  key(employeeID),
  key(customerID),
  foreign key (customerID) references vip(customerID) on update cascade on delete cascade,
  foreign key (employeeID) references chef(chefID) on update cascade on delete cascade
);


create table if not exists vip_driver (
  customerID int(11) not null,
  employeeID int(11) not null,
  primary key (customerID,employeeID),
  foreign key (customerID) references vip(customerID) on update cascade on delete cascade,
  foreign key (employeeID) references driver(driverID) on update cascade on delete cascade
);

create table if not exists vip_room (
  customerID int(11) not null,
  roomNumber int(11) not null,
  hotelID int(11) not null,
  primary key (customerID,roomNumber,hotelID),
  foreign key (customerID) references vip(customerID) on update cascade on delete cascade,
  foreign key (hotelID) references room (hotelID) on update cascade on delete cascade,
  foreign key (roomnumber) references room (roomnumber) on update cascade on delete cascade
);

drop trigger if exists hotel_date_insert;
delimiter |
create trigger hotel_date_insert before insert on hotel
for each row
begin
    if new.hotelRenoDate is not null and new.hotelRenoDate<new.hotelConstructionDate then
	set new.hotelRenoDate = new.hotelConstructionDate;
	end if;
end |
delimiter ;

drop trigger if exists hotel_date_update;
delimiter |
create trigger hotel_date_update before update on hotel
for each row
begin
    if new.hotelRenoDate is not null and new.hotelRenoDate<new.hotelConstructionDate then
	set new.hotelRenoDate = new.hotelConstructionDate;
	end if;
end |
delimiter ;

drop trigger if exists room_price_insert;
delimiter |
create trigger room_price_insert before insert on room
for each row
begin
	declare msg varchar(255);
    if new.price<0 then
	set msg = 'Price must be possitive';
	signal sqlstate '45000' set message_text = msg;
	end if;
end |
delimiter ;

drop trigger if exists room_price_update;
delimiter |
create trigger room_price_update before update on room
for each row
begin
	declare msg varchar(255);
    if new.price<0 then
	set msg = 'Price must be possitive';
	signal sqlstate '45000' set message_text = msg;
	end if;
end |
delimiter ;

drop trigger if exists employee_insert;
delimiter |
create trigger employee_insert before insert on employee
for each row
begin
	declare msg varchar(255);
    if new.employeeStartDate>new.employeeEndDate then
	set msg = 'Check Date';
	signal sqlstate '45000' set message_text = msg;
	end if;
	if new.employeeWage<0 then
	set msg = 'Wage must be possitive';
	signal sqlstate '45000' set message_text = msg;
	end if;
end |
delimiter ;

drop trigger if exists employee_update;
delimiter |
create trigger employee_update before update on employee
for each row
begin
	declare msg varchar(255);
    if new.employeeStartDate>new.employeeEndDate then
	set msg = 'Check Date';
	signal sqlstate '45000' set message_text = msg;
	end if;
	if new.employeeWage<0 then
	set msg = 'Wage must be possitive';
	signal sqlstate '45000' set message_text = msg;
	end if;
end |
delimiter ;


drop trigger if exists reservation_insert;
delimiter |
create trigger reservation_insert before insert on reservation
for each row
begin
	declare msg varchar(255);
	if (0<(select count(*)
		from reservation
		where roomNumber=new.roomNumber and 
		hotelID=new.hotelID and 
		((new.reservationStartDate>=reservationStartDate and new.reservationStartDate<=reservationEndDate)or
		(new.reservationEndDate>=reservationStartDate and new.reservationEndDate<=reservationEndDate)or
		(new.reservationStartDate<reservationStartDate and new.reservationEndDate>reservationEndDate))) or 
		(new.reservationStartDate<new.reservationAgreementDate) or (new.reservationEndDate<new.reservationAgreementDate) or 
		(new.reservationStartDate>new.reservationEndDate))
	then set msg = 'Either Dates are wrong or the room is reserved already';
	signal sqlstate '45000' set message_text = msg;
	end if;
		if new.reservationCost is null then
	set new.reservationCost=(select price
	from room
	where roomNumber=new.roomNumber and hotelID=new.hotelID)*(1+DATEDIFF(new.reservationEndDate,new.reservationStartDate));
	end if;
 end |
 delimiter ;
 
drop trigger if exists reservation_update;
delimiter |
create trigger reservation_update before update on reservation
for each row
begin
	declare msg varchar(255);
	if (0<(select count(*)
		from reservation
		where roomNumber=new.roomNumber and 
		hotelID=new.hotelID and 
		((new.reservationStartDate>=reservationStartDate and new.reservationStartDate<=reservationEndDate)or
		(new.reservationEndDate>=reservationStartDate and new.reservationEndDate<=reservationEndDate)or
		(new.reservationStartDate<reservationStartDate and new.reservationEndDate>reservationEndDate))) or 
		(new.reservationStartDate<new.reservationAgreementDate) or (new.reservationEndDate<new.reservationAgreementDate) or 
		(new.reservationStartDate>new.reservationEndDate))
	then set msg = 'Either Dates are wrong or the room is reserved already';
	signal sqlstate '45000' set message_text = msg;
	end if;
	if new.reservationCost is null then
	set new.reservationCost=(select price
	from room
	where roomNumber=new.roomNumber and hotelID=new.hotelID)*(1+DATEDIFF(new.reservationEndDate,new.reservationStartDate));
	end if;
 end |
 delimiter ;
 
select  customer.customerFirstName , customer.customerLastName , customertelephone.customerPhoneNumber
from customer
join customertelephone
on customer.customerID=customertelephone.customerID
order by customer.customerID;

select count(customerID) as CustomersNumber
from vip;

select r.roomNumber,h.hotelName FROM room as r,hotel as h
where r.price>=10 and r.price<=20 and r.hotelID=h.hotelID;
