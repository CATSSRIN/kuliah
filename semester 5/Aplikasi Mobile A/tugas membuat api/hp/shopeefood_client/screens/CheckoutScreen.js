import React, { useEffect, useState } from "react";
import { View, Text, StyleSheet, TouchableOpacity, TextInput,
Alert } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { postJSON, getJSON } from "../utils/api";
export default function CheckoutScreen({ route, navigation }) {
const { food_id, qty: qtyParam } = route.params || {};
const [food, setFood] = useState(null);
const [qty, setQty] = useState(qtyParam || 1);
useEffect(()=>{ (async ()=>{
const res = await getJSON(`food_detail.php?id=${food_id}`);
if (res.success) setFood(res.data);
})(); },[]);
if(!food) return <View
style={{flex:1,justifyContent:'center',alignItems:'center'}}><Text
>Memuat...</Text></View>;
const total = Number(food.price) * Number(qty);
const handleCheckout = async () => {
const token = await AsyncStorage.getItem('userToken');
if (!token) { Alert.alert("Perlu login","Silakan login");
return; }
const items = [{ food_id: food.id, qty: Number(qty) }];
const res = await postJSON('add_order.php', { items }, token);
if (res.success) { Alert.alert("Sukses","Pesanan dibuat",
[{text:'OK',onPress:()=>navigation.navigate('Orders')}]); }
else Alert.alert("Gagal", res.message || "Error");
};
return (
<View style={{flex:1,padding:12}}>
<Text
style={{fontSize:20,fontWeight:'700'}}>{food.name}</Text>
<Text style={{marginVertical:8}}>Harga: Rp
{Number(food.price).toLocaleString()}</Text>
<Text>Jumlah</Text>
<TextInput keyboardType="number-pad" value={String(qty)}
onChangeText={(t)=>setQty(Number(t)||1)}
style={{borderWidth:1,borderColor:'#ddd',padding:8,borderRadius:6,
marginVertical:8}} />
<Text style={{fontWeight:'700',marginBottom:12}}>Total: Rp
{Number(total).toLocaleString()}</Text>
<TouchableOpacity
style={{backgroundColor:'#00AA13',padding:12,borderRadius:8}}
onPress={handleCheckout}><Text
style={{color:'#fff',textAlign:'center'}}>Pesan
Sekarang</Text></TouchableOpacity>
</View>
);
}