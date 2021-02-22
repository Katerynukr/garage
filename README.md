# The project is a CRUD that can write into database a trucks(worker) that are repaired by mechankis that work in a garage.

**The database should have three tables.**
| Mechanics| |                            
| :---: | :---: | 
| id | int(11)| 
| name | varcahr(64)| 
| surname | varcahr(64)|   

| Trucks| |                            
| :---: | :---: | 
| id | int(11)| 
| maker| varcahr(255)| 
| plate| varcahr(20)|   
| make_year| tinyint(4)|   
| mehanic_notices| text|   
| mechanic_id | int(11)| 
***The connection between tables*** | **trucks.mechanic_id *------> mechanics.id***

| Users| |                            
| :---: | :---: | 
| id | int(11)| 
| email| varcahr(64)| 
| pass| password(128)|   

**The project has several specifications:**
- add, deleate and modify data from database. 
- trucks have a field (drop down) from which it is possible to select a mechanic
- filtration from created trucks by mechanic
- sorting mechanics by name and surname
- sign up, sign in and sign out funtionality
- notes field for truckscreated with redactor
- responsive design of project
- protection agains SQL injection

