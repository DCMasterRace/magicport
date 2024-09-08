# MagicPort Exam 

### **TECHS**
*PHP8.3  
*node v22.8.0  
*Laravel 11  
*Vue3  
*MySQL  
*Composer  

## **----Instruction----**

**1. RUN THE SQL SCRIPT**
- Execute the database.sql file

**2. Setting Up the API**
- cd to api folder.  `cd exam-api`
- Run all these commands:
```
composer install
./artisan key:generate
./artisan migrate:fresh
./artisan db:seed
```
- copy .env.example and create .env file and edit the database connection in the .env
- then run `./artisan serve`

**3. Setting up the front-end**
- cd to app folder. `cd exam-app`
- Run all these commands:
```
npm i && npm run dev -- --port 3000
```


Factory Pattern was used in the API. It's because the flow it provides a method for creating objects in a superclass but allows subclasses to change the type of object that will be created.
