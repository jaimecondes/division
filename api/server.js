
require("dotenv").config(); // load .env
const express = require("express");
const mysql = require("mysql2/promise");
const bcrypt = require("bcrypt");
const cors = require("cors");
const nodemailer = require("nodemailer");
const QRCode = require("qrcode");

const app = express();
const PORT = process.env.PORT || 5000;


app.use(cors());
app.use(express.json());

// MySQL connection using .env
const db = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
});

// Login route
app.post("/api/login", async (req, res) => {
  try {
    const { email, password } = req.body;

    // 🔍 Check if user exists
    const [rows] = await db.query("SELECT * FROM users WHERE email = ?", [email]);
    if (rows.length === 0) {
      return res.status(400).json({ message: "User not found" });
    }

    const user = rows[0];

    // ✅ Check password
    const match = await bcrypt.compare(password, user.password);
    if (!match) {
      return res.status(401).json({ message: "Invalid credentials" });
    }

    // 🔑 Generate token (dummy using bcrypt on email)
    const access_token = await bcrypt.hash(email + Date.now(), 10);

    // 🕒 Set expiry = 1 week from now
    const expiry = new Date();
    expiry.setDate(expiry.getDate() + 7);

    // 💾 Store token in DB
    await db.query(
      "INSERT INTO access_token (token, expiry, user_id) VALUES (?, ?, ?)",
      [access_token, expiry, user.id]
    );

    // ✅ Send response
    res.json({
      message: "Login successful",
      user: { id: user.id, email: user.email, level: user.user_level },
      token: access_token,
      expiry,
    });
  } catch (error) {
    console.error("Login error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

// Registration route
app.post("/api/register", async (req, res) => {
  try {
    const { email, password,level } = req.body;

    if (!email || !password) {
      return res.status(400).json({ message: "Email and password are required" });
    }

    const [existing] = await db.query("SELECT * FROM users WHERE email = ?", [email]);
    if (existing.length > 0) {
      return res.status(400).json({ message: "User already exists" });
    }

    const hashedPassword = await bcrypt.hash(password, 10);

    const [result] = await db.query(
      "INSERT INTO users (email, password,user_level) VALUES (?, ?, ?)",
      [email, hashedPassword, level]
    );

    res.status(201).json({
      message: "User registered successfully",
      user: { id: result.insertId, email },
    });
  } catch (error) {
    console.error("Registration error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

app.post("/api/user_register", async (req, res) => {
  try {
    const { email, first_name, last_name, user_type, mobile, position, company, school,plateno } = req.body;
    const level = 0;
    const qr_code = await bcrypt.hash(email, 5);

    // Check if user exists
    const [existing] = await db.query("SELECT * FROM users WHERE email = ?", [email]);
    if (existing.length > 0) {
      return res.status(400).json({ message: "User already exists" });
    }

    const comp = company ? company : school;

    // ✅ Fix SQL placeholders count (10 values needed)
    const [result] = await db.query(
      `INSERT INTO users (email, first_name, last_name, user_level, mobile_number, qr_code, is_admin, user_type, position, company_school,plateno) 
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [email, first_name, last_name, level, mobile, qr_code, 0, user_type, position, comp, plateno]
    );

    // ✅ Generate QR Code as PNG buffer
    const qrImage = await QRCode.toBuffer(qr_code);

    // ✅ Setup NodeMailer transport (example with Gmail SMTP)
    let transporter = nodemailer.createTransport({
      service: "gmail",
      auth: {
        user: process.env.EMAIL_USER, // your email
        pass: process.env.EMAIL_PASS, // your email app password
      },
    });

    // ✅ Send email with QR code attachment
    await transporter.sendMail({
      from: `"SDO Ormoc Admin Team" <${process.env.EMAIL_USER}>`,
      to: email,
      subject: "Check-In/Out System",
      text: `Dear ${first_name},\n\n Thank you for registering with the SDO Ormoc Automated Check-In/Out System.\n\n Your registration has been successfully recorded to check in and out during visits to the SDO Ormoc office. \n\n Download or Print your QR Code and present it at the Gate Entrance upon Check In or Check Out \n\n 
      If you have any questions or need assistance, feel free to contact through email at admin.r8.ormoccity@deped.gov.ph.\n\n 
      We look forward to your visit! \n\n
      Best regards,\n
      SDO Ormoc Admin Team`,
      attachments: [
        {
          filename: "qrcode.png",
          content: qrImage,
        },
      ],
    });

    res.status(201).json({
      message: "User registered successfully, email sent with QR code",
      user: { id: result.insertId, email, qr_code },
    });
  } catch (error) {
    console.error("Registration error:", error);
    res.status(500).json({ message: "Server error" });
  }
});
// get schools
app.get("/api/get_schools", async (req, res) => {
  try {
    
    const [school] = await db.query("SELECT * FROM school_office");
    return res.status(200).json({ data: school });

  } catch (error) {
    console.error("Fetch error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

app.get("/api/get_users", async (req, res) => {
  try {
    
    const [users] = await db.query("SELECT * FROM users where user_level=0");
    return res.status(200).json({ data: users });

  } catch (error) {
    console.error("Fetch error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

app.get("/api/get_logs", async (req, res) => {
  try {
    
    const [users] = await db.query("SELECT * FROM logs where DATE(log_time)=CURDATE() order by id desc");
    return res.status(200).json({ data: users });

  } catch (error) {
    console.error("Fetch error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

app.get("/api/get_oldlogs", async (req, res) => {
  try {
    
    const [users] = await db.query("SELECT * FROM logs order by id desc");
    return res.status(200).json({ data: users });

  } catch (error) {
    console.error("Fetch error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

app.post("/api/add_company", async (req, res) => {
  const {agency}=req.body;

  try {
    
    const [existing] = await db.query("SELECT * FROM company WHERE company_name = ?", [agency]);
    if (existing.length > 0) {
      return res.status(400).json({ message: "Company already exists" });
    }
    const [add] = await db.query("insert into company (company_name)values(?)", [agency]);
    return res.status(200).json({ message: "Company Added" });
  } catch (error) {
    console.error("Internal error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

app.post("/record-log", async (req, res) => {
  const { qr_code, type, log_time } = req.body;

  if (!qr_code || !type || !log_time) {
    return res.status(400).json({ success: 0, message: "Missing parameters" });
  }

  try {
    const connection = await mysql.createConnection(DB_CONFIG);

    // Check if QR code exists
    const [users] = await connection.execute(
      "SELECT id FROM users WHERE qr_code = ?",
      [qr_code]
    );

    if (users.length === 0) {
      return res.status(404).json({ success: 0, message: "QR Code invalid!" });
    }

    const userID = users[0].id;

    // Insert log
    await connection.execute(
      "INSERT INTO logs (log_time, qr_code, log_type, userID) VALUES (?, ?, ?, ?)",
      [log_time, qr_code, type, userID]
    );

    await connection.end();

    return res.json({ success: 1, log_time });
  } catch (error) {
    console.error("DB error:", error);
    return res.status(500).json({ success: 0, message: "Server error" });
  }
});

app.get("/api/get_positions", async (req, res) => {
  try {
    
    const [positions] = await db.query("SELECT * FROM positions");
    return res.status(200).json({ data: positions });

  } catch (error) {
    console.error("Fetch error:", error);
    res.status(500).json({ message: "Server error" });
  }
});
app.get("/api/get_companies", async (req, res) => {
  try {
    
    const [comp] = await db.query("SELECT * FROM company");
    return res.status(200).json({ data: comp });

  } catch (error) {
    console.error("Fetch error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

// Update user route
app.put("/api/users/:id", async (req, res) => {
  try {
    const { id } = req.params;
    const { email, password, level } = req.body;

    if (!email && !password) {
      return res.status(400).json({ message: "Nothing to update" });
    }

    // Build dynamic query
    const updates = [];
    const values = [];

    if (email) {
      updates.push("email = ?");
      values.push(email);
    }
     if (level) {
      updates.push("user_level = ?");
      values.push(level);
    }

    if (password) {
      const hashedPassword = await bcrypt.hash(password, 10);
      updates.push("password = ?");
      values.push(hashedPassword);
    }

    values.push(id); // user ID for WHERE clause

    const [result] = await db.query(
      `UPDATE users SET ${updates.join(", ")} WHERE id = ?`,
      values
    );

    if (result.affectedRows === 0) {
      return res.status(404).json({ message: "User not found" });
    }

    res.json({ message: "User updated successfully" });
  } catch (error) {
    console.error("Update error:", error);
    res.status(500).json({ message: "Server error" });
  }
});

app.listen(PORT, () => console.log(`Server running on http://localhost:${PORT}`));
