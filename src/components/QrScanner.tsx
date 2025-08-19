import React, { useRef, useEffect, useState } from "react";
import { BrowserMultiFormatReader } from "@zxing/browser";
import axios from "axios";
import config from "../config";
interface QrScannerProps {
  apiEndpoint: string;
}

const QrScanner: React.FC<QrScannerProps> = ({ apiEndpoint }) => {
  const videoRef = useRef<HTMLVideoElement | null>(null);
  const [scanResult, setScanResult] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const codeReader = new BrowserMultiFormatReader();
    if (videoRef.current) {
      codeReader
        .decodeFromVideoDevice(undefined, videoRef.current, async (result, err) => {
          if (result && result.getText() !== scanResult) {
            setScanResult(result.getText());
            setLoading(true);
            setError(null);
            try {
              await axios.post(apiEndpoint, { qr_code: result.getText() });
            } catch {
              setError("Failed to send QR code to API");
            } finally {
              setLoading(false);
            }
          }
          if (err && !(err.name === "NotFoundException")) {
            console.error(err);
            setError("Error accessing camera");
          }
        });
    }

    return () => codeReader.reset();
  }, [apiEndpoint, scanResult]);

  return (
    <div className="flex flex-col items-center p-4">
      <h2 className="text-xl font-semibold mb-4">QR Code Scanner</h2>
      <video ref={videoRef} className="w-80 h-80 border rounded-lg" />
      {loading && <p className="mt-4 text-blue-600">Sending QR code...</p>}
      {scanResult && !loading && <p className="mt-4 text-green-600">Scanned: {scanResult}</p>}
      {error && <p className="mt-4 text-red-600">{error}</p>}
    </div>
  );
};

export default QrScanner;
