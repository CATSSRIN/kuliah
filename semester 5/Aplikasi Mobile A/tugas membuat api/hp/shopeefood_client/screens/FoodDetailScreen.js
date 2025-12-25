import React, { useEffect, useState } from "react";
import { View, Text, TouchableOpacity, Image, StyleSheet,
TextInput, Alert } from "react-native";
import { getJSON } from "../utils/api";
import AsyncStorage from "@react-native-async-storage/async-storage";
export default async function FoodDetailScreen({ route, navigation }) {
const { id } = route.params;
const [food,setFood]=useState(null);
const [qty,setQty]=useState(1);
useEffect(()=>{ (async ()=>{
const res = await getJSON(`food_detail.php?id=${id}`);
if (res.success) setFood(res.data);
})(); },[]);
if(!food) return <View
style={{flex:1,justifyContent:'center',alignItems:'center'}}><Text
>Memuat...</Text></View>;
const handleGoCheckout = async () => {
const token = await AsyncStorage.getItem('userToken');
if (!token) { Alert.alert("Perlu login","Silakan login terlebih dahulu"); navigation.navigate('Login'); return; }
navigation.navigate('Checkout',{ food_id: food.id, qty: qty
});
};
return (
<View style={{flex:1,padding:12}}>
{food.image ? <Image source={{uri:`${(await import('../config/api')).API_BASE}/images/${food.image}`}}
style={{width:'100%',height:200,borderRadius:8}} /> : null}
<Text
style={{fontSize:20,fontWeight:'700',marginTop:12}}>{food.name}</Text>
<Text style={{color:'#666',marginTop:6}}>Rp
{Number(food.price).toLocaleString()}</Text>
<Text style={{marginTop:12}}>{food.description}</Text>
<View
style={{flexDirection:'row',alignItems:'center',marginTop:12}}>
<TouchableOpacity style={styles.qtyBtn}
onPress={()=>setQty(Math.max(1,qty-1))}><Text>-
</Text></TouchableOpacity>
<TextInput value={String(qty)}
onChangeText={(t)=>setQty(Number(t)||1)} keyboardType="number-pad"
style={styles.qtyInput}/>
<TouchableOpacity style={styles.qtyBtn}
onPress={()=>setQty(qty+1)}><Text>+</Text></TouchableOpacity>
</View>
<TouchableOpacity style={styles.btn}
onPress={handleGoCheckout}><Text
style={{color:'#fff',textAlign:'center'}}>Checkout</Text></TouchableOpacity>
</View>
);
}
const styles = StyleSheet.create({
qtyBtn:{padding:10,backgroundColor:'#eee',borderRadius:6},
qtyInput:{width:60,textAlign:'center',marginHorizontal:8,borderWidth:1,borderColor:'#ddd',borderRadius:6,padding:6},
btn:{backgroundColor:'#00AA13',padding:12,borderRadius:8,marginTop
:14}
});
