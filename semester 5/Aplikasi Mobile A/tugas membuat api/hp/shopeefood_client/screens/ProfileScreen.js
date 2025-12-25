import React, { useEffect, useState } from "react";
import { View, Text, TouchableOpacity, Alert } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
export default function ProfileScreen({ navigation }) {
const [user,setUser]=useState(null);
useEffect(()=>{ (async ()=>{
const u = await AsyncStorage.getItem('user');
if(u) setUser(JSON.parse(u));
})(); },[]);
const logout = async ()=> {
await AsyncStorage.removeItem('userToken');
await AsyncStorage.removeItem('user');
navigation.replace('Login');
};
return (
<View style={{flex:1,alignItems:'center',padding:20}}>
<Text style={{fontSize:20,fontWeight:'700'}}>{user?.name ||
user?.email || 'Pengguna'}</Text>
<Text style={{color:'#666'}}>{user?.email}</Text>
<TouchableOpacity onPress={logout}
style={{marginTop:20,backgroundColor:'#ff3333',padding:12,borderRadius:8}}>
<Text style={{color:'#fff'}}>Logout</Text>
</TouchableOpacity>
{user?.role === 'admin' && (
<TouchableOpacity
onPress={()=>navigation.navigate('AddMenu')}
style={{marginTop:12,backgroundColor:'#0077ff',padding:12,borderRadius:8}}>
<Text style={{color:'#fff'}}>Tambah Menu</Text>
</TouchableOpacity>
)}
</View>
);
}