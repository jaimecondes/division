import React from "react";
import { Routes, Route } from "react-router-dom";
import Login from './pages/Login';
import AdminDashboard from "./pages/AdminDashboard";
import Registration from "./pages/Registration";
import Realtime from "./pages/Realtime";
import Scanner from "./pages/Scanner";
function App() {
  return (
    <>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/admin/dashboard" element={<AdminDashboard />} />
        <Route path="/admin/registration" element={<Registration />} />
        <Route path="/admin/realtime" element={<Realtime />} />
        <Route path="/admin/scanner" element={<Scanner />} />
      </Routes>
    </>
  );
}

export default App;
