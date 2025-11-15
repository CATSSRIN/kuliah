import React, { useState, useEffect } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, FlatList, ScrollView, Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Screen Register
function RegisterScreen({ onNavigate }) {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleRegister = async () => {
    setError('');
    if (!name || !email || !password) {
      setError('Semua field harus diisi!');
      return;
    }

    try {
      const users = await AsyncStorage.getItem('users');
      const usersList = users ? JSON.parse(users) : [];
      
      const userExists = usersList.some(u => u.email === email);
      if (userExists) {
        setError('Email sudah terdaftar!');
        return;
      }

      usersList.push({ name, email, password });
      await AsyncStorage.setItem('users', JSON.stringify(usersList));
      Alert.alert('Sukses', 'Akun berhasil dibuat! Silahkan login.');
      onNavigate('login');
    } catch (err) {
      setError('Gagal membuat akun!');
    }
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.innerContainer}>
        <Text style={styles.title}>Buat Akun</Text>
        <Text style={styles.subtitle}>Bergabunglah dengan Ujek</Text>
        <TextInput style={styles.input} placeholder="Nama Lengkap" placeholderTextColor="#999" value={name} onChangeText={(text) => { setName(text); setError(''); }} />
        <TextInput style={styles.input} placeholder="Email" placeholderTextColor="#999" keyboardType="email-address" value={email} onChangeText={(text) => { setEmail(text); setError(''); }} />
        <TextInput style={styles.input} placeholder="Password" placeholderTextColor="#999" secureTextEntry value={password} onChangeText={(text) => { setPassword(text); setError(''); }} />
        {error ? <Text style={styles.errorText}>{error}</Text> : null}
        <TouchableOpacity style={styles.button} onPress={handleRegister}>
          <Text style={styles.buttonText}>Daftar</Text>
        </TouchableOpacity>
        <TouchableOpacity onPress={() => onNavigate('login')}>
          <Text style={styles.linkText}>Sudah punya akun? Masuk di sini</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

// Screen Login
function LoginScreen({ onNavigate }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleLogin = async () => {
    setError('');
    if (!email || !password) {
      setError('Email dan password harus diisi!');
      return;
    }

    try {
      const users = await AsyncStorage.getItem('users');
      const usersList = users ? JSON.parse(users) : [];
      
      const user = usersList.find(u => u.email === email && u.password === password);
      if (user) {
        await AsyncStorage.setItem('currentUser', JSON.stringify(user));
        onNavigate('home');
      } else {
        setError('Tidak dikenal');
      }
    } catch (err) {
      setError('Gagal login!');
    }
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.innerContainer}>
        <Text style={styles.title}>Masuk</Text>
        <Text style={styles.subtitle}>Kembali ke akun Anda</Text>
        <TextInput style={styles.input} placeholder="Email" placeholderTextColor="#999" keyboardType="email-address" value={email} onChangeText={(text) => { setEmail(text); setError(''); }} />
        <TextInput style={styles.input} placeholder="Password" placeholderTextColor="#999" secureTextEntry value={password} onChangeText={(text) => { setPassword(text); setError(''); }} />
        {error ? <Text style={styles.errorText}>{error}</Text> : null}
        <TouchableOpacity style={styles.button} onPress={handleLogin}>
          <Text style={styles.buttonText}>Masuk</Text>
        </TouchableOpacity>
        <TouchableOpacity onPress={() => onNavigate('register')}>
          <Text style={styles.linkText}>Belum punya akun? Daftar di sini</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

// Screen Home
function HomeScreen({ onNavigate }) {
  const [userName, setUserName] = useState('');
  const services = [
    { id: '1', name: 'URide', icon: '🚗' },
    { id: '2', name: 'UCar', icon: '🚙' },
    { id: '3', name: 'UFood', icon: '🍽️' },
    { id: '4', name: 'USend', icon: '📦' },
    { id: '5', name: 'UMart', icon: '🛍️' },
    { id: '6', name: 'UPulsa', icon: '📱' },
  ];

  useEffect(() => {
    const loadUser = async () => {
      const user = await AsyncStorage.getItem('currentUser');
      if (user) {
        const userData = JSON.parse(user);
        setUserName(userData.name);
      }
    };
    loadUser();
  }, []);

  return (
    <View style={styles.homeWrapper}>
      <ScrollView style={styles.homeContent}>
        <View style={styles.header}>
          <Text style={styles.greeting}>Selamat Datang, {userName}!</Text>
          <Text style={styles.subGreeting}>Pilih layanan yang Anda inginkan</Text>
        </View>
        <FlatList data={services} numColumns={3} scrollEnabled={false} keyExtractor={(item) => item.id} renderItem={({ item }) => (
          <TouchableOpacity style={styles.serviceCard}>
            <Text style={styles.serviceIcon}>{item.icon}</Text>
            <Text style={styles.serviceName}>{item.name}</Text>
          </TouchableOpacity>
        )} />
      </ScrollView>
      <View style={styles.tabNavigator}>
        <TouchableOpacity style={styles.tabItem}>
          <Text style={styles.tabText}>🏠 Home</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.tabItem} onPress={() => onNavigate('orders')}>
          <Text style={styles.tabText}>📋 Orders</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.tabItem} onPress={() => onNavigate('login')}>
          <Text style={styles.tabText}>👤 Keluar</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

// Screen Orders
function OrdersScreen({ onNavigate }) {
  const orders = [
    { id: '1', service: 'URide', amount: 'Rp 50.000', status: 'Selesai', date: '2025-11-15' },
    { id: '2', service: 'UFood', amount: 'Rp 75.000', status: 'Sedang Diproses', date: '2025-11-14' },
    { id: '3', service: 'UMart', amount: 'Rp 120.000', status: 'Selesai', date: '2025-11-13' },
    { id: '4', service: 'UPulsa', amount: 'Rp 50.000', status: 'Selesai', date: '2025-11-12' },
  ];

  return (
    <View style={styles.homeWrapper}>
      <ScrollView style={styles.homeContent}>
        <View style={styles.header}>
          <Text style={styles.greeting}>Pesanan Saya</Text>
          <Text style={styles.subGreeting}>Riwayat pesanan Anda</Text>
        </View>
        <FlatList data={orders} scrollEnabled={false} keyExtractor={(item) => item.id} renderItem={({ item }) => (
          <View style={styles.orderCard}>
            <View style={styles.orderHeader}>
              <Text style={styles.orderService}>{item.service}</Text>
              <Text style={[styles.orderStatus, { color: item.status === 'Selesai' ? '#DA70D6' : '#FF9800' }]}>{item.status}</Text>
            </View>
            <View style={styles.orderDetails}>
              <Text style={styles.orderAmount}>{item.amount}</Text>
              <Text style={styles.orderDate}>{item.date}</Text>
            </View>
          </View>
        )} />
      </ScrollView>
      <View style={styles.tabNavigator}>
        <TouchableOpacity style={styles.tabItem} onPress={() => onNavigate('home')}>
          <Text style={styles.tabText}>🏠 Home</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.tabItem}>
          <Text style={styles.tabText}>📋 Orders</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.tabItem} onPress={() => onNavigate('login')}>
          <Text style={styles.tabText}>👤 Keluar</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}

export default function App() {
  const [currentScreen, setCurrentScreen] = useState('login');
  const navigateTo = (screen) => { setCurrentScreen(screen); };

  return (
    <View style={styles.appContainer}>
      {currentScreen === 'register' && <RegisterScreen onNavigate={navigateTo} />}
      {currentScreen === 'login' && <LoginScreen onNavigate={navigateTo} />}
      {currentScreen === 'home' && <HomeScreen onNavigate={navigateTo} />}
      {currentScreen === 'orders' && <OrdersScreen onNavigate={navigateTo} />}
    </View>
  );
}

const styles = StyleSheet.create({
  appContainer: { flex: 1, backgroundColor: '#fff' },
  container: { flex: 1, backgroundColor: '#fff' },
  innerContainer: { padding: 24, justifyContent: 'center', marginTop: 50 },
  title: { fontSize: 28, fontWeight: 'bold', color: '#DA70D6', marginBottom: 8 },
  subtitle: { fontSize: 14, color: '#666', marginBottom: 32 },
  input: { borderWidth: 1, borderColor: '#e0e0e0', borderRadius: 8, padding: 14, marginBottom: 14, fontSize: 14, backgroundColor: '#f9f9f9' },
  button: { backgroundColor: '#DA70D6', borderRadius: 8, padding: 14, alignItems: 'center', marginTop: 20, marginBottom: 16 },
  buttonText: { color: '#fff', fontSize: 16, fontWeight: '600' },
  linkText: { color: '#DA70D6', fontSize: 14, textAlign: 'center', textDecorationLine: 'underline' },
  errorText: { color: '#FF3333', fontSize: 12, marginBottom: 12, textAlign: 'center', fontWeight: 'bold' },
  homeWrapper: { flex: 1, backgroundColor: '#fff', flexDirection: 'column' },
  homeContent: { flex: 1, backgroundColor: '#fff' },
  header: { backgroundColor: '#DA70D6', paddingVertical: 24, paddingHorizontal: 16, marginBottom: 20 },
  greeting: { fontSize: 24, fontWeight: 'bold', color: '#fff' },
  subGreeting: { fontSize: 14, color: '#e0f2f1', marginTop: 4 },
  serviceCard: { flex: 1, margin: 8, padding: 16, backgroundColor: '#f5f5f5', borderRadius: 12, alignItems: 'center', justifyContent: 'center', minHeight: 100, elevation: 2 },
  serviceIcon: { fontSize: 32, marginBottom: 8 },
  serviceName: { fontSize: 12, fontWeight: '600', color: '#333', textAlign: 'center' },
  orderCard: { margin: 12, padding: 16, backgroundColor: '#f9f9f9', borderRadius: 8, borderLeftWidth: 4, borderLeftColor: '#DA70D6' },
  orderHeader: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 8 },
  orderService: { fontSize: 16, fontWeight: '600', color: '#333' },
  orderStatus: { fontSize: 12, fontWeight: '600', paddingHorizontal: 8, paddingVertical: 4, backgroundColor: '#f0f0f0', borderRadius: 4 },
  orderDetails: { flexDirection: 'row', justifyContent: 'space-between' },
  orderAmount: { fontSize: 14, fontWeight: '600', color: '#DA70D6' },
  orderDate: { fontSize: 12, color: '#999' },
  tabNavigator: { height: 70, backgroundColor: '#fff', borderTopWidth: 1, borderTopColor: '#e0e0e0', flexDirection: 'row', justifyContent: 'space-around', alignItems: 'center' },
  tabItem: { flex: 1, alignItems: 'center', justifyContent: 'center', paddingVertical: 8 },
  tabText: { fontSize: 12, fontWeight: '500', color: '#DA70D6' },
});