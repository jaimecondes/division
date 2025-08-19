import { useState, useEffect } from "react";
import axios from "axios";

import Header from "../components/header";
import SideNav from "../components/SideNave";
import QrScanner from "../components/QrScanner";
import config from "../config";
export default function Scanner() {

const api=`${config.API_ENDPOINT}/record-log`;
  return (
   <div className="h-screen flex flex-col">
    <Header />
    <div className="flex flex-1">
    <SideNav />
    <div className="flex items-left justify-left min-h-screen bg-gray-100">
      <div className="w-full max-w-lg bg-white shadow-lg rounded-2xl p-8">
        <QrScanner apiEndpoint={api}/>
        </div> 
    </div>   
    </div> 
    </div>
  );
}
