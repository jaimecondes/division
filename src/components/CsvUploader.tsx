import React, { useState } from "react";
import Papa from "papaparse";
import axios from "axios";
import config from "../config"; // ✅ adjust path

export default function CsvUserUpload({fetchUsers}:Props) {
  const [file, setFile] = useState(null);
  const [progress, setProgress] = useState(0);
  const [status, setStatus] = useState("");
  const [isUploading, setIsUploading] = useState(false);

  const handleFileChange = (e) => {
    setFile(e.target.files[0]);
    setProgress(0);
    setStatus("");
  };

  const handleUpload = () => {
    if (!file) {
      alert("Please select a CSV file first.");
      return;
    }

    setIsUploading(true);
    setStatus("Parsing CSV...");

    Papa.parse(file, {
      header: true,
      skipEmptyLines: true,
      complete: async (results) => {
        const rows = results.data;

        if (rows.length === 0) {
          setStatus("No data found in CSV.");
          setIsUploading(false);
          return;
        }

        setStatus("Starting upload...");

        let successCount = 0;
        for (let i = 0; i < rows.length; i++) {
          const user = {
            user_type: rows[i].user_type,
            first_name: rows[i].first_name,
            last_name: rows[i].last_name,
            position: rows[i].position,
            company: rows[i].school_office, // backend expects `company_school`
            school: rows[i].school_office,
            mobile: rows[i].mobile_number,
            email: rows[i].email,
          };

          try {
            await axios.post(`${config.API_ENDPOINT}/user_register`, user);
            successCount++;
          } catch (error) {
            console.error("Error uploading row:", user, error);
          }

          // update progress %
          setProgress(Math.round(((i + 1) / rows.length) * 100));
          setStatus(`Uploaded ${i + 1}/${rows.length} users`);
        }
        await fetchUsers();
        setIsUploading(false);
        setStatus(`Upload complete. ${successCount}/${rows.length} users registered successfully.`);
      },
    });
  };

  return (
    <div className="p-6 bg-white shadow-lg rounded-lg w-full max-w-lg mx-auto">
      <h2 className="text-lg font-bold mb-4">Upload Users via CSV</h2>

      {/* File Input */}
      <input
        type="file"
        accept=".csv"
        onChange={handleFileChange}
        className="mb-4"
      />

      {/* Upload Button */}
      <button
        onClick={handleUpload}
        disabled={!file || isUploading}
        className="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:opacity-50"
      >
        {isUploading ? "Uploading..." : "Upload CSV"}
      </button>

      {/* Progress Bar */}
      {isUploading && (
        <div className="mt-4">
          <div className="w-full bg-gray-200 rounded-full h-4">
            <div
              className="bg-green-500 h-4 rounded-full transition-all duration-300"
              style={{ width: `${progress}%` }}
            ></div>
          </div>
          <p className="text-sm mt-2">{status}</p>
        </div>
      )}

      {/* Final Status */}
      {!isUploading && status && (
        <p className="text-sm mt-2 text-gray-700">{status}</p>
      )}
    </div>
  );
}
