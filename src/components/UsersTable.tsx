import { useState, useMemo } from "react";

export default function UsersTable({ users }) {
  const [search, setSearch] = useState("");
  const [sortConfig, setSortConfig] = useState({ key: null, direction: "asc" });
  const [currentPage, setCurrentPage] = useState(1);
  const pageSize = 10; // ✅ 10 users per page

  // ✅ Filter + Sort users
  const filteredUsers = useMemo(() => {
    let data = [...users];

    // 🔍 Search
    if (search) {
      data = data.filter(
        (u) =>
          u.first_name.toLowerCase().includes(search.toLowerCase()) ||
          u.last_name.toLowerCase().includes(search.toLowerCase()) ||
          u.email.toLowerCase().includes(search.toLowerCase()) ||
          u.company_school.toLowerCase().includes(search.toLowerCase())
      );
    }

    // ⬆️⬇️ Sorting
    if (sortConfig.key) {
      data.sort((a, b) => {
        const aVal = a[sortConfig.key]?.toString().toLowerCase() || "";
        const bVal = b[sortConfig.key]?.toString().toLowerCase() || "";
        if (aVal < bVal) return sortConfig.direction === "asc" ? -1 : 1;
        if (aVal > bVal) return sortConfig.direction === "asc" ? 1 : -1;
        return 0;
      });
    }

    return data;
  }, [users, search, sortConfig]);

  // ✅ Pagination logic
  const totalPages = Math.ceil(filteredUsers.length / pageSize);
  const paginatedUsers = filteredUsers.slice(
    (currentPage - 1) * pageSize,
    currentPage * pageSize
  );

  // 🔄 Toggle sort order
  const requestSort = (key) => {
    let direction = "asc";
    if (sortConfig.key === key && sortConfig.direction === "asc") {
      direction = "desc";
    }
    setSortConfig({ key, direction });
  };

  return (
    <div className="p-4 bg-white shadow-md rounded-lg">
      {/* 🔍 Search bar */}
      <div className="mb-4">
        <input
          type="text"
          placeholder="Search users..."
          value={search}
          onChange={(e) => {
            setSearch(e.target.value);
            setCurrentPage(1); // reset to page 1 when searching
          }}
          className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        />
      </div>

      {/* 📋 Table */}
      <table className="w-full border-collapse border border-gray-300 rounded-lg">
        <thead className="bg-gray-100">
          <tr>
            <th
              className="p-2 border cursor-pointer"
              onClick={() => requestSort("first_name")}
            >
              User{" "}
              {sortConfig.key === "first_name" &&
                (sortConfig.direction === "asc" ? "▲" : "▼")}
            </th>
            <th
              className="p-2 border cursor-pointer"
              onClick={() => requestSort("user_type")}
            >
              User Type{" "}
              {sortConfig.key === "user_type" &&
                (sortConfig.direction === "asc" ? "▲" : "▼")}
            </th>
            <th
              className="p-2 border cursor-pointer"
              onClick={() => requestSort("company_school")}
            >
              Company/School{" "}
              {sortConfig.key === "company_school" &&
                (sortConfig.direction === "asc" ? "▲" : "▼")}
            </th>
            <th
              className="p-2 border cursor-pointer"
              onClick={() => requestSort("email")}
            >
              Email{" "}
              {sortConfig.key === "email" &&
                (sortConfig.direction === "asc" ? "▲" : "▼")}
            </th>
            <th
              className="p-2 border cursor-pointer"
              onClick={() => requestSort("mobile_number")}
            >
              Cell#{" "}
              {sortConfig.key === "mobile_number" &&
                (sortConfig.direction === "asc" ? "▲" : "▼")}
            </th>
          </tr>
        </thead>
        <tbody>
          {paginatedUsers.length > 0 ? (
            paginatedUsers.map((user) => (
              <tr key={user.id} className="hover:bg-gray-50">
                <td className="p-2 border">
                  {user.first_name} {user.last_name}
                </td>
                <td className="p-2 border">{user.user_type}</td>
                <td className="p-2 border">{user.company_school}</td>
                <td className="p-2 border">{user.email}</td>
                <td className="p-2 border">{user.mobile_number}</td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan="5" className="p-4 text-center text-gray-500">
                No users found
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {/* 📌 Pagination Controls */}
      <div className="flex justify-between items-center mt-4">
        <button
          disabled={currentPage === 1}
          onClick={() => setCurrentPage((p) => p - 1)}
          className="px-3 py-1 border rounded disabled:opacity-50"
        >
          Previous
        </button>

        <div className="space-x-1">
          {Array.from({ length: totalPages }, (_, i) => (
            <button
              key={i + 1}
              onClick={() => setCurrentPage(i + 1)}
              className={`px-3 py-1 border rounded ${
                currentPage === i + 1
                  ? "bg-blue-600 text-white"
                  : "hover:bg-gray-100"
              }`}
            >
              {i + 1}
            </button>
          ))}
        </div>

        <button
          disabled={currentPage === totalPages || totalPages === 0}
          onClick={() => setCurrentPage((p) => p + 1)}
          className="px-3 py-1 border rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </div>
  );
}
