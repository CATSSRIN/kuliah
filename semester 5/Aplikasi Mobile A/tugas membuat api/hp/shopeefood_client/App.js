import React, { useEffect, useState } from "react";
import { NavigationContainer } from "@react-navigation/native";
import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { createBottomTabNavigator } from "@react-navigation/bottom-tabs";
import { Ionicons } from "@expo/vector-icons";
import AsyncStorage from "@react-native-async-storage/async-storage";
import LoginScreen from "./screens/LoginScreen";
import RegisterScreen from "./screens/RegisterScreen";
import HomeScreen from "./screens/HomeScreen";
import FoodDetailScreen from "./screens/FoodDetailScreen";
import CheckoutScreen from "./screens/CheckoutScreen";
import OrdersScreen from "./screens/OrdersScreen";
import ProfileScreen from "./screens/ProfileScreen";
import AddMenuScreen from "./screens/AddMenuScreen";
const Stack = createNativeStackNavigator();
const Tab = createBottomTabNavigator();
function MainTabs(){
return (
<Tab.Navigator screenOptions={({route})=>({
headerShown:false,
tabBarIcon: ({color,size})=>{
let icon = route.name === 'Home' ? 'home' : route.name === 'Orders' ? 'receipt' : 'person';
return <Ionicons name={icon} size={size} color={color} />;
}
})}>
<Tab.Screen name="Home" component={HomeScreen} />
<Tab.Screen name="Orders" component={OrdersScreen} />
<Tab.Screen name="Profile" component={ProfileScreen} />
</Tab.Navigator>
);
}
export default function App(){
const [loading,setLoading] = useState(true);
const [isLoggedIn,setIsLoggedIn] = useState(false);
useEffect(()=>{ (async ()=>{
const t = await AsyncStorage.getItem('userToken');
setIsLoggedIn(!!t);
setLoading(false);
})(); },[]);
if (loading) return null;
return (
<NavigationContainer>
<Stack.Navigator>
<Stack.Screen name="MainTabs" component={MainTabs} options={{headerShown:false}} />
<Stack.Screen name="FoodDetail" component={FoodDetailScreen} options={{title:"Detail"}} />
<Stack.Screen name="Orders" component={OrdersScreen} options={{title:"Orders"}} />
<Stack.Screen name="Login" component={LoginScreen} options={{title:"Login"}} />
<Stack.Screen name="Checkout" component={CheckoutScreen} options={{title:"Checkout"}} />
<Stack.Screen name="AddMenu" component={AddMenuScreen} options={{title:"Tambah Menu"}} />
<Stack.Screen name="Register" component={RegisterScreen} options={{headerShown:false}} />
</Stack.Navigator>
</NavigationContainer>
);
}