---------- Flow of the project -------------------------------------------
User will register itself on click of register
After register user redirect to dashboard page
User make appointment on click Create Appointment
After Creating Appointment user will see they appointment date and time and status
User can change the status of the appointment
On same day and same time user cannot create appointment
Admin will login and see list of appointment and filter the appointment and make status change

------ How to run the Project --------------------
1 make connection with your local database
2 php artisan migrate
3 php artisan db:seed it will run your seed file where predefine data
4 npm run dev this is for your UI package
5 php artisan serve

---------- Database design flow --------------
1 mst_roles table
2 mst_status table
3 users table in user table roleID work as foreign key connect to mst_roles table
4 appointment table in appointment table statusID work as foreign key connect to mst_status and userID work as foreign key connect to users table
