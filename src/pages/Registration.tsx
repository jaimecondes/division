import { useState, useEffect } from "react";
import axios from "axios";
import config from "../config";
import Select from "react-select";
import UsersTable from "../components/UsersTable";
import CsvUserUpload from "../components/CsvUploader";
import Header from "../components/header";
import SideNav from "../components/SideNave";
export default function Registration() {
  const [isOpen, setIsOpen] = useState(false);
 const [disabledButton, setDisabledButton] = useState<string | null>(null);
  const [uType, setUtype] =useState('personnel');
  const [positions, setPositions] =useState([]);
  const [schools, setSchools] =useState([]);
  const [users, setUsers] =useState([]);
  const [formSelect, setFormSelect] =useState(''); 
  const [agency, setAgency] =useState(''); 
  const [is_sending, setIssending]=useState(false);
  const [companies, setCompany] =useState([]);
  const [formData, setFormData] = useState({
    first_name: "",
    last_name: "",
    user_type: "",
    mobile: "",
    email: "",
    position:"",
    company: "",
    school: "",
  });
 useEffect(()=>{
    fetchPositions();
    fetchSchools();
    fetchCompanies();
    fetchUsers();
 },[]);

 const fetchPositions=async()=>{
    try {
      const res = await axios.get(
        `${config.API_ENDPOINT}/get_positions`
      );
      console.log("Positions", res.data);
      setPositions(Array.isArray(res.data.data) ? res.data.data : [res.data.data]);
      //setPositions(res.data);
    } catch (err) {
      console.error("Fetch error:", err);
     
    }
 }
 const fetchSchools=async()=>{
    try {
      const res = await axios.get(
        `${config.API_ENDPOINT}/get_schools`
      );
      console.log("schools", res.data);
      setSchools(Array.isArray(res.data.data) ? res.data.data : [res.data.data]);
      //setSchools(res.data);
    } catch (err) {
      console.error("Fetch error:", err);
     
    }
 }
 const fetchCompanies=async()=>{
    try {
      const res = await axios.get(
        `${config.API_ENDPOINT}/get_companies`
      );
      console.log("companies", res.data);
      setCompany(Array.isArray(res.data.data) ? res.data.data : [res.data.data]);
      //setSchools(res.data);
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
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({ ...formData, [name]: value });
    (name=='user_type'? setUtype(value):'');
    console.log(uType);
  };

  const handleSubmit = async (e) => {
  e.preventDefault();

  if(!formData.user_type){
    alert("select user type first");
    return
  }
  if(!formData.first_name){
    alert("input your first name");
    return
  }
  if(!formData.last_name){
    alert("input your last name");
    return
  }
  if(!formData.email){
    alert("email is required");
    return
  }
  setIssending(true);

  try {
    const res = await axios.post(
      `${config.API_ENDPOINT}/user_register`,
      formData
    );

    console.log("Registration success:", res.data);
    alert("User registered successfully!");

  } catch (err) {
    console.error("Registration error:", err);

    if (err.response) {
      // ✅ Server responded with a status outside 2xx
      if (err.response.status === 400) {
        alert(err.response.data.message || "Bad Request");
      } else {
        alert(err.response.data.message || "Server error occurred.");
      }
    } else if (err.request) {
      // ✅ No response from server
      alert("No response from server. Please try again later.");
    } else {
      // ✅ Something went wrong setting up request
      alert("Unexpected error occurred.");
    }
  } finally {
    setIssending(false);
  }
};
const handleSelect=(type: string)=>{
    setFormSelect(type);
    console.log(type);
    setDisabledButton(type);
}
const handleConfirm=async()=>{
  try {
    const res = await axios.post(
      `${config.API_ENDPOINT}/add_company`,
      {agency}
    );

    console.log("Adding  success:", res.data);
    alert("Company added successfully!");
    fetchCompanies();
  } catch (err) {
    console.error("Adding error:", err);

    if (err.response) {
      // ✅ Server responded with a status outside 2xx
      if (err.response.status === 400) {
        alert(err.response.data.message || "Bad Request");
      } else {
        alert(err.response.data.message || "Server error occurred.");
      }
    } else if (err.request) {
      // ✅ No response from server
      alert("No response from server. Please try again later.");
    } else {
      // ✅ Something went wrong setting up request
      alert("Unexpected error occurred.");
    }
  } finally {
    setIssending(false);
    setIsOpen(false);
  }
}

  return (
   <div className="h-screen flex flex-col">
    <Header />
    <div className="flex flex-1">
    <SideNav />
    {isOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
          <div className="bg-white rounded-xl p-6 w-[400px] shadow-lg">
            <h2 className="text-xl font-semibold mb-4">Company/Institution</h2>
            <input
              type="text"
              name="last_name"
              value={agency}
              onChange={(e)=>setAgency(e.target.value)}
              placeholder="Enter Company"
              className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
              
            />

            <div className="mt-2 flex justify-end space-x-2">
              <button
                onClick={() => setIsOpen(false)}
                className="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition"
              >
                Close
              </button>
              <button
                onClick={handleConfirm}
                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
              >
                Confirm
              </button>
            </div>
          </div>
        </div>
      )}

    
    
   
    
    <div className="flex items-left justify-left min-h-screen bg-gray-100">
      <div className="w-full max-w-lg bg-white shadow-lg rounded-2xl p-8">
        <h2 className="text-2xl font-bold text-center text-gray-700 mb-6">
          User Registration
        </h2>
        <div className="flex">
             <button
             onClick={()=>handleSelect('form')}
             disabled={disabledButton == "form"}
              className={`m-4 w-full py-2 rounded-lg transition text-white ${
          disabledButton === "form" ? "bg-gray-400 cursor-not-allowed" : "bg-blue-600 hover:bg-blue-700"
        }`}>
             
             Fill up Form
        </button>
       
             <button 
             onClick={()=>handleSelect('upload')}
             disabled={disabledButton == "upload"}
            className={`m-4 w-full py-2 rounded-lg transition text-white ${
            disabledButton === "upload" ? "bg-gray-400 cursor-not-allowed" : "bg-blue-600 hover:bg-blue-700"
            }`}
      >
             Upload Users in CSV file
        </button>
        </div>
       
        
        {formSelect==='form'?(
          <div>
           <form className="space-y-4" onSubmit={handleSubmit}>
          {/* First Name */}
          <div>
            <label className="block text-gray-600 mb-1">User Type</label>
            <select
              required
              name="user_type"
              value={formData.user_type}
              onChange={handleChange}
              className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            >   
               <option value="">Select User Type</option>  
              <option value="personnel">DepEd Personnel</option>
              <option value="others">Others</option>
            </select>
          </div>
          
          <div>
            <label className="block text-gray-600 mb-1">First Name</label>
            <input
              type="text"
              name="first_name"
              value={formData.first_name}
              onChange={handleChange}
              placeholder="Enter first name"
              className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          {/* Last Name */}
          <div>
            <label className="block text-gray-600 mb-1">Last Name</label>
            <input
              type="text"
              name="last_name"
              value={formData.last_name}
              onChange={handleChange}
              placeholder="Enter last name"
              className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

         <div>
            <label className="block text-gray-600 mb-1">Position</label>
            <Select
                name="position"
                value={
                positions
                    .map((sc) => ({ value: sc.position, label: sc.position }))
                    .find((opt) => opt.value === formData.position) || null
                }
                onChange={(selected) =>
                setFormData({ ...formData, position: selected ? selected.value : "" })
                }
                options={positions.map((sc) => ({
                value: sc.position,
                label: sc.position,
                }))}
                placeholder="Select or type position..."
                isSearchable={true}
                className="w-full"
            />
          </div>

        {uType=='personnel' && (<>
        <div>
            <label className="block text-gray-600 mb-1">School/Office</label>
           <Select
                name="school"
                value={
                schools
                    .map((sc) => ({ value: sc.school_office_name, label: sc.school_office_name }))
                    .find((opt) => opt.value === formData.school) || null
                }
                onChange={(selected) =>
                setFormData({ ...formData, school: selected ? selected.value : "" })
                }
                options={schools.map((sc) => ({
                value: sc.school_office_name,
                label: sc.school_office_name,
                }))}
                placeholder="Select or type school..."
                isSearchable={true}
                className="w-full"
            />
          </div>
        </>)}
         {uType=='others' && (<>
        <div>
            <label className="block text-gray-600 mb-1">Agency/Institution</label>
           <Select
              name="company"
              value={formData.company} // must be an option object
              onChange={(selected) => setFormData({ ...formData, company: selected })}
              options={companies.map((sc) => ({
                value: sc.company_name,
                label: sc.company_name,
                id: sc.id, // preserve id for saving
              }))}
              placeholder="Select or type company..."
              isSearchable={true}
              className="w-full"
            />
            <span
            onClick={() => setIsOpen(true)}
            className="inline-block w-[100px] mt-1 py-2 rounded-lg transition text-white cursor-pointer
                      bg-blue-600 hover:bg-blue-700 text-center font-medium shadow-md hover:shadow-lg"
          >
            Add
          </span>

          </div>
        </>)}   
          

          {/* Mobile */}
          <div>
            <label className="block text-gray-600 mb-1">Mobile Number</label>
            <input
              type="tel"
              name="mobile"
              value={formData.mobile}
              onChange={handleChange}
              placeholder="09XXXXXXXXX"
              className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          {/* Email */}
          <div>
            <label className="block text-gray-600 mb-1">Email</label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              placeholder="Enter email"
              className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          {/* Password
          <div>
            <label className="block text-gray-600 mb-1">Password</label>
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              placeholder="Enter password"
              className="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
              required
            />
          </div> */}

         <button
            type="submit"
            disabled={is_sending}
            className={`w-full py-2 rounded-lg transition text-white 
                ${is_sending ? "bg-gray-400 cursor-not-allowed" : "bg-blue-600 hover:bg-blue-700"}`}
            >
            {is_sending ? (
                <>
                <i className="fa fa-spinner fa-spin mr-2"></i>
                Registering...
                </>
            ) : (
                "Register"
            )}
            </button>
        </form>
            </div>
            
        ):(
          <CsvUserUpload fetchUsers={fetchUsers}/>  
        )}
        
      </div>

      <div className="ml-5 w-full bg-white rounded-2xl shadow-lg  p-8">
        <UsersTable users={users} />
      </div>
    </div>
    </div> 
    </div>
  );
}
