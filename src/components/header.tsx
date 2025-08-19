import { useNavigate } from "react-router-dom";
import { LogOut} from "lucide-react";
export default function Header(){
    const navigate =useNavigate();
     const handleLogout = () => {
    // 🧹 Clear all stored session data
   
    localStorage.removeItem("token");
    localStorage.removeItem("expiry");
    localStorage.removeItem("email");
    localStorage.removeItem("user_level");

    // 🚪 Redirect to login page
    navigate("/");
  };
    return(<>
    <header className="flex items-center justify-between bg-blue-600 text-white px-6 py-4 shadow-md">
        <h1 className="text-xl font-bold">Admin Panel</h1>
        <button
          onClick={handleLogout}
          className="flex items-center gap-2 bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg"
        >
          <LogOut size={18} /> Logout
        </button>
      </header>
        </>)
}