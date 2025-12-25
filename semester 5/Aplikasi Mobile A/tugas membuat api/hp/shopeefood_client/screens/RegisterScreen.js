import React, { useState } from "react";
import { View, Text, TextInput, TouchableOpacity, StyleSheet,
Alert } from "react-native";
import { postJSON } from "../utils/api";
export default function RegisterScreen({ navigation }) {
const [name,setName]=useState("");
const [email,setEmail]=useState("");
const [password,setPassword]=useState("");
const handleRegister = async () => {
if(!email||!password) return Alert.alert("Error","Email & password wajib");
const res = await postJSON("register.php", { name, email,
password });
if (res.success) { Alert.alert("Sukses","Registrasi berhasil"); navigation.replace("Login"); }
else Alert.alert("Gagal", res.message || "Registrasi gagal");
};
return (
<View style={styles.container}>
<Text style={styles.title}>Register</Text>
<TextInput placeholder="Nama" style={styles.input}
value={name} onChangeText={setName} />
<TextInput placeholder="Email" style={styles.input}
value={email} onChangeText={setEmail} />
<TextInput placeholder="Password" style={styles.input}
value={password} secureTextEntry onChangeText={setPassword} />
<TouchableOpacity style={styles.btn}
onPress={handleRegister}><Text
style={styles.btnText}>Daftar</Text></TouchableOpacity>
<TouchableOpacity
onPress={()=>navigation.navigate('Login')}><Text
style={{marginTop:12}}>Sudah punya akun?
Login</Text></TouchableOpacity>
</View>
);
}
const styles=StyleSheet.create({
container:{flex:1,justifyContent:"center",padding:20},
title:{fontSize:24,fontWeight:"bold",marginBottom:20,textAlign:"center"},
input:{borderWidth:1,borderColor:"#ddd",padding:12,borderRadius:8,
marginBottom:10},
btn:{backgroundColor:"#00AA13",padding:12,borderRadius:8,alignItems:"center"},
btnText:{color:"#fff",fontWeight:"bold"}
});
