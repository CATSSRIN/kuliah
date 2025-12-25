import React, { useState } from "react";
import { View, TextInput, TouchableOpacity, Text, Image, Alert }
from "react-native";
import * as ImagePicker from 'expo-image-picker';
import AsyncStorage from "@react-native-async-storage/async-storage";
import { API_BASE } from "../config/api";
export default function AddMenuScreen({ navigation }) {
const [name,setName]=useState("");
const [desc,setDesc]=useState("");
const [price,setPrice]=useState("");
const [restaurantId,setRestaurantId]=useState("1");
const [image,setImage]=useState(null);
const pickImage = async () => {
const r = await
ImagePicker.requestMediaLibraryPermissionsAsync();
if (!r.granted) return Alert.alert("Perlu ijin");
const p = await
ImagePicker.launchImageLibraryAsync({quality:0.7});
if (!p.cancelled) setImage(p.uri);
};
const upload = async () => {
const token = await AsyncStorage.getItem('userToken');
if (!token) { Alert.alert("Login required"); return; }
const form = new FormData();
form.append('name', name);
form.append('description', desc);
form.append('price', price);
form.append('restaurant_id', restaurantId);
if (image) {
const filename = image.split('/').pop();
const match = /\.(\w+)$/.exec(filename);
const type = match ? `image/${match[1]}` : 'image';
form.append('image', { uri: image, name: filename, type });
}
try {
const res = await fetch(`${API_BASE}/add_food.php`, {
method: 'POST',
headers: { 'Authorization': `Bearer ${token}`, 'Content-Type':'multipart/form-data' },
body: form
});
const json = await res.json();
if (json.success) { Alert.alert("Sukses", json.message);
navigation.goBack(); }
else Alert.alert("Gagal", json.message || "error");
} catch (e) { Alert.alert("Error","Tidak bisa connect"); }
};
return (
<View style={{flex:1,padding:12}}>
<TextInput placeholder="Nama makanan" value={name}
onChangeText={setName}
style={{borderWidth:1,padding:8,borderRadius:8,marginBottom:8}} />
<TextInput placeholder="Deskripsi" value={desc}
onChangeText={setDesc}
style={{borderWidth:1,padding:8,borderRadius:8,marginBottom:8}} />
<TextInput placeholder="Harga" value={price}
keyboardType="numeric" onChangeText={setPrice}
style={{borderWidth:1,padding:8,borderRadius:8,marginBottom:8}} />
<TouchableOpacity onPress={pickImage}
style={{backgroundColor:'#eee',padding:10,alignItems:'center',borderRadius:8}}><Text>Pilih Gambar</Text></TouchableOpacity>
{image && <Image source={{uri:image}}
style={{width:120,height:120,marginTop:8}} />}
<TouchableOpacity onPress={upload}
style={{backgroundColor:'#00AA13',padding:12,alignItems:'center',borderRadius:8,marginTop:12}}><Text
style={{color:'#fff'}}>Upload</Text></TouchableOpacity>
</View>
);
}