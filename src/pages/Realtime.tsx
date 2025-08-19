import { useState, useEffect } from "react";
import axios from "axios";
import config from "../config";
import Header from "../components/header";
import SideNav from "../components/SideNave";
import { ChartAreaIcon, DownloadIcon } from "lucide-react";

export default function Realtime() {

  const [logs, setLogs] =useState([]);
  const [oldlogs, setOldLogs] =useState([]);
  const [inCount, setIncount]=useState(0);
  const [outCount, setOutcount]=useState(0);
  const [users, setUsers] =useState([]);
  
 
 useEffect(()=>{
    setInterval(()=>{
         fetchLogs();
    },3000);
   fetchOldLogs();
    fetchUsers();
 },[]);

 const fetchLogs=async()=>{
    try {
      const res = await axios.get(
        `${config.API_ENDPOINT}/get_logs`
      );
      
      setLogs(Array.isArray(res.data.data) ? res.data.data : [res.data.data]);
       const logsData = Array.isArray(res.data.data) ? res.data.data : [res.data.data];
    
      console.log(res.data.data)
      const inCount = logsData.filter(log => log.log_type === "IN").length;
      const outCount = logsData.filter(log => log.log_type === "OUT").length;
      setIncount(inCount);
      setOutcount(outCount);
    } catch (err) {
      console.error("Fetch error:", err);
     
    }
 }
  const fetchOldLogs=async()=>{
    try {
      const res = await axios.get(
        `${config.API_ENDPOINT}/get_oldlogs`
      );
      
      setOldLogs(Array.isArray(res.data.data) ? res.data.data : [res.data.data]);
      console.log(res.data.data)
    } catch (err) {
      console.error("Fetch error:", err);
     
    }
 }
  const fetchUsers=async()=>{
    try {
      const res = await axios.get(
        `${config.API_ENDPOINT}/get_users`
      );
      console.log("users", res.data);
      setUsers(Array.isArray(res.data.data) ? res.data.data : [res.data.data]);
      //setSchools(res.data);
    } catch (err) {
      console.error("Fetch error:", err);
     
    }
 }
const getUserInitials = (userID: number) => {
  const user = users.find((u) => u.id === userID);
  if (!user) return "?";
  return `${user.first_name[0]}${user.last_name[0]}`.toUpperCase();
};
  const getUserName = (userID: number) => {
    const user = users.find((u) => u.id === userID);
    return user ? `${user.first_name} ${user.last_name}` : "Unknown User";
  };
  const getUserCompany = (userID: number) => {
    const user = users.find((u) => u.id === userID);
    return user ? `${user.company_school}` : "";
  };
  const formatLogTime = (datetime: string | Date) => {
  const date = new Date(datetime);
  return date.toLocaleString("en-US", {
    month: "short",       // "Aug"
    day: "2-digit",       // "18"
    year: "numeric",      // "2025"
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hour12: true,         // enable AM/PM
  });
};

const exportLogsToCSV = (logs) => {
  if (!logs || logs.length === 0) {
    alert("No logs available to export.");
    return;
  }

  // Get CSV headers from object keys
  const headers = Object.keys(logs[0]);
  const csvRows = [];

  // Add headers
  csvRows.push(headers.join(","));

  // Add rows
  logs.forEach(log => {
    const values = headers.map(header => `"${log[header] ?? ""}"`);
    csvRows.push(values.join(","));
  });

  // Create CSV string
  const csvString = csvRows.join("\n");

  // Create a Blob and download link
  const blob = new Blob([csvString], { type: "text/csv" });
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement("a");
  a.setAttribute("hidden", "");
  a.setAttribute("href", url);
  a.setAttribute("download", "logs.csv");
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
};

  return (
   <div className="h-screen flex flex-col">
    <Header />
  
    <div className="flex flex-1">
    <SideNav />
    
    <div className="flex flex-1 items-left justify-left bg-gray-100">
      <div className="w-full max-w-lg bg-white shadow-lg h-full rounded-2xl p-8">
       <div className="w-full mt-5 bg-blue-500 rounded-tl-2xl rounded-tr-2xl">
          <h1 className="text-2xl font-bold text-white text-center mb-6">
            Recent User
          </h1>
        </div>
       
       
        <div className="flex">
          {logs[0] && (
            
            <div className="w-full bg-white rounded-2xl shadow-lg flex flex-col items-center">
                 <div className="w-20 h-20 flex items-center justify-center rounded-full bg-blue-500 flex items-center justify-center mb-4 text-white text-xl font-bold">
                    {getUserInitials(logs[0].userID)}
                </div>
                <div className="bg-default p-2">

                <p className="mb-3 text-center rounded text-2xl font-bold text-dark-799">
                    Name: <span className="rounded p-1">{getUserName(logs[0].userID)}</span>
                </p>
                <p className="text-2xl text-dark-799">Time: <span className="rounded p-1">{formatLogTime(logs[0].log_time)}</span></p>
                <p className=" text-2xl text-dark-799">Type: <span className="rounded p-1">{logs[0].log_type}</span></p>
                </div>

                <div className="bg-gray p-2 mt-2">
                <p className="m-3 text-1xl font-bold text-gray-700">
                    Company/School: {getUserCompany(logs[0].userID)}
                </p>
                </div>
            </div>
            )}
        </div>
         <div className="w-full mt-5 bg-blue-500 rounded-tl-2xl rounded-tr-2xl">
          <h1 className="text-2xl font-bold text-white text-center mb-6">
            Check In/Out
          </h1>
        </div>
       <div className="flex">
          
            
            <div className="flex flex-1 w-full bg-white rounded-2xl shadow-lg flex flex-col items-center">
                IN
                <span className="text-2xl font-bold text-center text-gray-700 mb-6">{inCount}</span>
            </div>
            <div className="flex flex-1 w-full bg-white rounded-2xl shadow-lg flex flex-col items-center">
               OUT
            <span className="text-2xl font-bold text-center text-gray-700 mb-6">{outCount}</span>
            </div>
            
        </div>
      </div>


      <div className="ml-5 w-full bg-white rounded-2xl shadow-lg  p-8">
         <div className="w-full mt-5 bg-blue-500 rounded-tl-2xl rounded-tr-2xl">
          <h1 className="text-2xl font-bold text-white text-center mb-6">
            Today
          </h1>
        </div>
        {logs.slice(1).map((log) => (
            <div
              key={log.id}
              className="w-full bg-white rounded-2xl shadow-lg p-3 m-2"
            >
             <div className="bg-default">
                 <p className="font-semibold text-gray-700">
                Name: {getUserName(log.userID)}
              </p>
              <p className="text-gray-500">Time: {formatLogTime(log.log_time)}</p>
              <p className="text-gray-500">Type: {log.log_type}</p>
             </div>  

             <div className="bg-default">
                 <p className="font-semibold text-gray-700">
                Company/School: {getUserCompany(log.userID)}
              </p>
             
             </div>  
             
            </div>
          ))}
      </div>

      <div className="ml-5 w-full bg-white  rounded-2xl shadow-lg  p-8">
        <div className="w-full bg-gray-100 flex justify-end p-2">
          <button 
            onClick={() => exportLogsToCSV(oldlogs)} 
            className="px-6 py-2 rounded-lg transition text-white bg-blue-600 hover:bg-blue-700"
          >Download
          
          </button>
        </div>
         <div className="w-full mt-5 bg-blue-500 rounded-tl-2xl rounded-tr-2xl">
          <h1 className="text-2xl font-bold text-white text-center mb-6">
           Logs History
          </h1>
        </div>
        {oldlogs.map((log) => (
            <div
              key={log.id}
              className="w-full bg-white rounded-2xl shadow-lg p-3 m-2"
            >
             <div className="bg-default">
                 <p className="font-semibold text-gray-700">
                Name: {getUserName(log.userID)}
              </p>
              <p className="text-gray-500">Time: {formatLogTime(log.log_time)}</p>
              <p className="text-gray-500">Type: {log.log_type}</p>
             </div>  

             <div className="bg-default">
                 <p className="font-semibold text-gray-700">
                Company/School: {getUserCompany(log.userID)}
              </p>
             
             </div>  
             
            </div>
          ))}
      </div>
    </div>
    </div> 
    </div>
  );
}
