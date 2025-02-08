# **Yoga Class Booking - Web Application**
**A platform for booking yoga classes** 🧘‍♂️

## 🔗 **Project Repository**
👉 [GitHub Repository](https://github.com/YourUsername/yoga-class-booking)

## ✨ **Motivation**
I chose to develop this application to simplify the process of booking yoga classes, providing an intuitive interface for users and an efficient system for instructors. This project focuses on freelance yoga teachers and small yoga studios that need a digital solution to manage their class schedules.

## ⚙️ **Key Features**
✔️ **User registration and login** with JWT authentication  
✔️ **Book and cancel** yoga classes with ease  
✔️ **Message board** for updates and announcements  
✔️ **Notification system** to remind users of their bookings  
✔️ **Modern UI** built with **React + Tailwind CSS**  
✔️ **Secure backend** developed in **PHP & MySQL**  

## 🛠 **Technologies Used**
- **Frontend**: React.js, Tailwind CSS  
- **Backend**: PHP, MySQL, Laravel  
- **Authentication**: JSON Web Tokens (JWT)
  
📌 **Goal**: Digitize the class booking process to simplify the work of yoga instructors and enhance the user experience.

---

# 🚀 **Setup Instructions**

## 📌 1. Clone the Repository

Open the terminal and navigate to the folder where you want to download the project:
```bash
cd ~/Documents  # Or wherever you want.
```
Clone repo:
```bash
git clone https://github.com/ra1nb93/yoga-class-booking.git
```
Enter on the folder Project:
```bash
cd yoga-class-booking
```

---

## 📌 2. Configure the Database

1. **Create the MySQL database:**
   ```bash
   mysql -u root -p -e "CREATE DATABASE yoga_app;"
   ```
2. **Import the SQL file to create tables and populate data:**
   ```bash
   mysql -u root -p yoga_app < yoga_app.sql
   ```
3. **Verify that the tables were created successfully:**
   ```bash
   mysql -u root -p -e "USE yoga_app; SHOW TABLES;"
   ```
   Se vedi tabelle come `users`, `bookings`, `classes`, significa che il database è stato importato correttamente.

---

## 📌 3. Start the PHP Backend

The backend runs with pure PHP, so you can start it directly from the project's main folder:
```bash
php -S localhost:8000
```
The backend will be accessible at: [http://localhost:8000](http://localhost:8000).

---

## 📌 4. Start the React Frontend

1. **Navigate to the frontend folder:**
   ```bash
   cd yoga-frontend
   ```
2. **Install dependencies:**
   ```bash
   npm install
   ```
3. **Start the frontend:**
   ```bash
   npm run dev
   ```
Open the browser and visit: [http://localhost:5173](http://localhost:5173).

---

✅ The project is now set up for local use and connected to the imported database! 🚀
