// screens/AboutScreen.js
import React from 'react';
import { View, Text, StyleSheet, ScrollView } from 'react-native';

export default function AboutScreen() {
  return (
    <ScrollView style={styles.container}>
      <View style={styles.content}>
        {/* Icon Aplikasi */}
        <Text style={styles.appIcon}>🍜</Text>

        {/* Tentang Aplikasi */}
        <Text style={styles.sectionTitle}>Tentang Aplikasi</Text>
        <Text style={styles.description}>
          Katalog Kuliner Nusantara adalah aplikasi mobile yang menampilkan 
          berbagai macam kuliner khas daerah dari seluruh Indonesia. Aplikasi 
          ini dibuat menggunakan React Native dengan Expo framework dan 
          dilengkapi dengan fitur CRUD (Create, Read, Update, Delete) untuk 
          mengelola data produk kuliner.
        </Text>

        {/* Fitur Aplikasi */}
        <Text style={styles.sectionTitle}>Fitur Aplikasi</Text>
        <View style={styles.featureContainer}>
          <Text style={styles.featureItem}>✅ Tampilan katalog produk kuliner</Text>
          <Text style={styles.featureItem}>✅ Detail informasi produk</Text>
          <Text style={styles.featureItem}>✅ Tambah produk baru</Text>
          <Text style={styles.featureItem}>✅ Edit data produk</Text>
          <Text style={styles.featureItem}>✅ Hapus produk</Text>
          <Text style={styles.featureItem}>✅ Stack Navigation</Text>
        </View>

        {/* Teknologi yang Digunakan */}
        <Text style={styles.sectionTitle}>Teknologi</Text>
        <View style={styles.techContainer}>
          <Text style={styles.techItem}>⚛️ React Native</Text>
          <Text style={styles.techItem}>📱 Expo Snack</Text>
          <Text style={styles.techItem}>🧭 React Navigation</Text>
          <Text style={styles.techItem}>💾 State Management (useState)</Text>
        </View>

        {/* Informasi Mahasiswa */}
        <View style={styles.studentInfo}>
          <Text style={styles.sectionTitle}>Informasi Mahasiswa</Text>
          <View style={styles.infoRow}>
            <Text style={styles.infoLabel}>Nama:</Text>
            <Text style={styles.infoValue}>Caezarlov nugraha</Text>
          </View>
          <View style={styles.infoRow}>
            <Text style={styles.infoLabel}>NPM:</Text>
            <Text style={styles.infoValue}>23081010182</Text>
          </View>
          <View style={styles.infoRow}>
            <Text style={styles.infoLabel}>Kelas:</Text>
            <Text style={styles.infoValue}>Aplikasi Mobile A</Text>
          </View>
          <View style={styles.infoRow}>
            <Text style={styles.infoLabel}>Mata Kuliah:</Text>
            <Text style={styles.infoValue}>Aplikasi Mobile</Text>
          </View>
          <View style={styles.infoRow}>
            <Text style={styles.infoLabel}>Dosen:</Text>
            <Text style={styles.infoValue}>Muhammad Muharrom Al Haromainy, S.Kom., M.Kom.</Text>
          </View>
        </View>

        {/* Footer */}
        <View style={styles.footer}>
          <Text style={styles.footerText}>
            © 2025 Katalog Kuliner Nusantara
          </Text>
          <Text style={styles.footerText}>
            UPN "Veteran" Jawa Timur
          </Text>
        </View>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  content: {
    padding: 20,
  },
  appIcon: {
    fontSize: 80,
    textAlign: 'center',
    marginVertical: 20,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 24,
    marginBottom: 12,
  },
  description: {
    fontSize: 16,
    color: '#555',
    lineHeight: 24,
    textAlign: 'justify',
  },
  featureContainer: {
    backgroundColor: '#f9f9f9',
    padding: 15,
    borderRadius: 8,
  },
  featureItem: {
    fontSize: 15,
    color: '#333',
    marginBottom: 8,
  },
  techContainer: {
    backgroundColor: '#f0f8ff',
    padding: 15,
    borderRadius: 8,
  },
  techItem: {
    fontSize: 15,
    color: '#333',
    marginBottom: 8,
  },
  studentInfo: {
    backgroundColor: '#fff3e0',
    padding: 15,
    borderRadius: 8,
    marginTop: 12,
  },
  infoRow: {
    flexDirection: 'row',
    marginBottom: 10,
  },
  infoLabel: {
    fontSize: 15,
    fontWeight: '600',
    color: '#555',
    width: 120,
  },
  infoValue: {
    fontSize: 15,
    color: '#333',
    flex: 1,
  },
  footer: {
    marginTop: 30,
    marginBottom: 20,
    alignItems: 'center',
  },
  footerText: {
    fontSize: 14,
    color: '#999',
    textAlign: 'center',
    marginBottom: 4,
  },
});
