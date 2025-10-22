// App.js
import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

// Import Context Provider
import { ProductProvider } from './context/ProductContext';

// Import screens
import HomeScreen from './screens/HomeScreen';
import DetailScreen from './screens/DetailScreen';
import FormScreen from './screens/FormScreen';
import AboutScreen from './screens/AboutScreen';

const Stack = createNativeStackNavigator();

// Splash Screen Component
const SplashScreen = () => {
  return (
    <View style={styles.splashContainer}>
      <Text style={styles.splashEmoji}>🍜</Text>
      <Text style={styles.splashTitle}>Katalog Kuliner</Text>
      <Text style={styles.splashSubtitle}>Nusantara</Text>
    </View>
  );
};

export default function App() {
  const [showSplash, setShowSplash] = useState(true);

  useEffect(() => {
    // Tampilkan splash screen selama 2 detik
    const timer = setTimeout(() => {
      setShowSplash(false);
    }, 2000);

    return () => clearTimeout(timer);
  }, []);

  if (showSplash) {
    return <SplashScreen />;
  }

  return (
    // PENTING: Wrap dengan ProductProvider
    <ProductProvider>
      <NavigationContainer>
        <Stack.Navigator
          screenOptions={{
            headerStyle: {
              backgroundColor: '#e74c3c',
            },
            headerTintColor: '#fff',
            headerTitleStyle: {
              fontWeight: 'bold',
            },
          }}
        >
          <Stack.Screen 
            name="Home" 
            component={HomeScreen}
            options={{ title: 'Katalog Kuliner Nusantara' }}
          />
          <Stack.Screen 
            name="Detail" 
            component={DetailScreen}
            options={{ title: 'Detail Produk' }}
          />
          <Stack.Screen 
            name="Form" 
            component={FormScreen}
            options={{ title: 'Tambah/Edit Produk' }}
          />
          <Stack.Screen 
            name="About" 
            component={AboutScreen}
            options={{ title: 'Tentang Aplikasi' }}
          />
        </Stack.Navigator>
      </NavigationContainer>
    </ProductProvider>
  );
}

const styles = StyleSheet.create({
  splashContainer: {
    flex: 1,
    backgroundColor: '#e74c3c',
    justifyContent: 'center',
    alignItems: 'center',
  },
  splashEmoji: {
    fontSize: 100,
    marginBottom: 20,
  },
  splashTitle: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 8,
  },
  splashSubtitle: {
    fontSize: 18,
    color: '#fff',
  },
});
