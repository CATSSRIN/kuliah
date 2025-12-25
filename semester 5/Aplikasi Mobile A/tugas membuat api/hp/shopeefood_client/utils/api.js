import { API_BASE } from "../config/api";
export async function postJSON(path, body, token=null){
const headers = { "Content-Type": "application/json" };
if (token) headers["Authorization"] = `Bearer ${token}`;
const res = await fetch(`${API_BASE}/${path}`, {
method: "POST",
headers,
body: JSON.stringify(body)
});
return res.json();
}
export async function getJSON(path, token=null){
const headers = { "Content-Type": "application/json" };
if (token) headers["Authorization"] = `Bearer ${token}`;
const res = await fetch(`${API_BASE}/${path}`, { method: "GET",
headers });
return res.json();
}