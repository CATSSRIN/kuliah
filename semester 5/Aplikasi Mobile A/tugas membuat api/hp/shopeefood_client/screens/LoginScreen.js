import React, { useState } from "react";
import { View, Text, TextInput, TouchableOpacity, StyleSheet,
Alert } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { postJSON } from "../utils/api";
export default function LoginScreen({ navigation }) {
const [email,setEmail] = useState("");
const [password,setPassword] = useState("");
const handleLogin = async () => {
if(!email||!password) return Alert.alert("Error","Email & password wajib");const res = await postJSON("login.php", { email, password });
if (res.success) {
await AsyncStorage.setItem("userToken", res.data.token);
await AsyncStorage.setItem("user",
JSON.stringify(res.data));
navigation.replace("MainTabs");
} else Alert.alert("Gagal", res.message || "Login gagal");
};
return (
<View style={styles.container}>
<Text style={styles.title}>Login</Text>
<TextInput placeholder="Email" style={styles.input}
value={email} onChangeText={setEmail} />
<TextInput placeholder="Password" style={styles.input}
value={password} secureTextEntry onChangeText={setPassword} />
<TouchableOpacity style={styles.btn}
onPress={handleLogin}><Text
style={styles.btnText}>Masuk</Text></TouchableOpacity>
<TouchableOpacity
onPress={()=>navigation.navigate('Register')}><Text
style={{marginTop:12}}>Belum punya akun?
Daftar</Text></TouchableOpacity>
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