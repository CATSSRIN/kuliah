import React, { useEffect, useState } from "react";
import { View, Text, FlatList, TouchableOpacity, StyleSheet } from
"react-native";
import { getJSON } from "../utils/api";
export default function HomeScreen({ navigation }) {
const [foods,setFoods]=useState([]);
useEffect(()=>{ loadFoods(); },[]);
const loadFoods = async () => {
const res = await getJSON("foods.php");
if (Array.isArray(res)) setFoods(res);
else if (res.success === false) alert(res.message || "Gagal ambil data");
};
return (
<View style={{flex:1,padding:12}}>
<Text style={styles.title}>Menu Makanan</Text>
<FlatList data={foods} keyExtractor={i=>String(i.id)}
renderItem={({item})=>(
<TouchableOpacity style={styles.card}
onPress={()=>navigation.navigate('FoodDetail',{id:item.id})}>
<Text style={{fontWeight:'700'}}>{item.name}</Text>
<Text style={{color:'#666'}}>{item.restaurant}</Text>
<Text style={{marginTop:6}}>Rp
{Number(item.price).toLocaleString()}</Text>
</TouchableOpacity>
)} />
</View>
);
}
const styles=StyleSheet.create({
title:{fontSize:22,fontWeight:'700',marginBottom:10},
card:{backgroundColor:'#fff',padding:12,borderRadius:8,marginBottom:10,elevation:2}
});
