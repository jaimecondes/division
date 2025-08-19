import React from "react";
import { Link, useLocation } from "react-router-dom";
import { LayoutDashboard, Activity, Users, QrCodeIcon } from "lucide-react";

const SideNav: React.FC = () => {
  const location = useLocation();

  const navItems = [
   
    { name: "Realtime Logs", path: "/admin/realtime", icon: <Activity size={18} /> },
    { name: "Users", path: "/admin/registration", icon: <Users size={18} /> },
    
  ];

  return (
    <aside className="w-64 bg-gray-100 border-r h-screen">
      <nav className="flex flex-col p-4 space-y-2">
        {navItems.map((item) => {
          const isActive = location.pathname === item.path;
          return (
            <Link
              key={item.path}
              to={item.path}
              className={`flex items-center gap-2 px-4 py-2 rounded-lg text-left ${
                isActive ? "bg-blue-500 text-white" : "hover:bg-gray-200"
              }`}
            >
              {item.icon} {item.name}
            </Link>
          );
        })}
      </nav>
    </aside>
  );
};

export default SideNav;
