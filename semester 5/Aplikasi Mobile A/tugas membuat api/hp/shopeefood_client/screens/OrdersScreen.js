import React, { useState, useCallback } from "react";
import { View, Text, FlatList } from "react-native";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { getJSON } from "../utils/api";
import { useFocusEffect } from "@react-navigation/native";
export default function OrdersScreen() {
const [orders,setOrders]=useState([]);
useFocusEffect(
useCallback(()=>{ (async ()=>{
const token = await AsyncStorage.getItem('userToken');
if (!token) return;
const res = await getJSON('orders.php', token);
if (res.success) setOrders(res.data || []);
})(); },[]));
return (
<View style={{flex:1,padding:12}}>
<Text
style={{fontSize:20,fontWeight:'700',marginBottom:10}}>Riwayat
Pesanan</Text>
<FlatList data={orders} keyExtractor={o=>String(o.id)}
renderItem={({item})=>(
<View
style={{backgroundColor:'#fff',padding:12,borderRadius:8,marginBottom:8}}>
<Text style={{fontWeight:'700'}}>Order #{item.id} — Rp
{item.total}</Text>
<Text>Status: {item.status}</Text>
{item.items && item.items.map(it=>(
<Text key={it.id}> - {it.food_name} x {it.qty} (Rp
{it.price})</Text>
))}
</View>
)} />
</View>
);
}