import { useState } from "react";

import {LayoutDashboard, Users, Activity } from "lucide-react";
import Header from "../components/header";
import Registration from "./Registration";
import SideNav from "../components/SideNave";
export default function AdminDashboard() {
  const [active, setActive] = useState("dashboard");
     


  return (
    <div className="h-screen flex flex-col">
      {/* Header */}
      
     <Header />
      {/* Main Content */}
      <div className="flex flex-1">
        {/* Sidebar */}
        <SideNav />
        {/* Content Area */}
        <main className="flex-1 p-6 bg-gray-50">
          {active === "dashboard" && <h2 className="text-2xl font-semibold">📊 Dashboard Overview</h2>}
          {active === "logs" && <h2 className="text-2xl font-semibold">📡 Realtime Logs</h2>}
          {active === "users" && <Registration />}
        </main>
      </div>
    </div>
  );
}
