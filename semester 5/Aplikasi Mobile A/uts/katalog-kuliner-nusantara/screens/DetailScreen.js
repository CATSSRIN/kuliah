// screens/DetailScreen.js
import React from 'react';
import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Alert } from 'react-native';
import { useProducts } from '../context/ProductContext';

export default function DetailScreen({ route, navigation }) {
  const { productId } = route.params;
  
  // Ambil fungsi dari Context
  const { getProductById, deleteProduct } = useProducts();
  
  // Get product by ID
  const product = getProductById(productId);

  if (!product) {
    return (
      <View style={styles.container}>
        <Text style={styles.errorText}>Produk tidak ditemukan</Text>
      </View>
    );
  }

  const handleEdit = () => {
    // Navigate ke form dengan productId untuk edit
    navigation.navigate('Form', { 
      productId: product.id,
      mode: 'edit'
    });
  };

  const handleDelete = () => {
    Alert.alert(
      'Konfirmasi Hapus',
      `Apakah Anda yakin ingin menghapus "${product.name}"?`,
      [
        { text: 'Batal', style: 'cancel' },
        {
          text: 'Hapus',
          style: 'destructive',
          onPress: () => {
            deleteProduct(product.id);
            // Kembali ke home page setelah delete
            navigation.goBack();
            Alert.alert('Sukses', 'Produk berhasil dihapus!');
          },
        },
      ]
    );
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.imageContainer}>
        <Text style={styles.image}>{product.image}</Text>
      </View>

      <View style={styles.contentContainer}>
        <Text style={styles.name}>{product.name}</Text>
        
        <View style={styles.infoRow}>
          <Text style={styles.label}>Kategori:</Text>
          <Text style={styles.value}>{product.category}</Text>
        </View>

        <View style={styles.infoRow}>
          <Text style={styles.label}>Asal Daerah:</Text>
          <Text style={styles.value}>{product.origin}</Text>
        </View>

        <View style={styles.infoRow}>
          <Text style={styles.label}>Harga:</Text>
          <Text style={styles.price}>Rp {product.price.toLocaleString('id-ID')}</Text>
        </View>

        <View style={styles.descriptionContainer}>
          <Text style={styles.label}>Deskripsi:</Text>
          <Text style={styles.description}>{product.description}</Text>
        </View>

        {/* Tombol Aksi */}
        <View style={styles.actionButtons}>
          <TouchableOpacity 
            style={[styles.button, styles.editButton]}
            onPress={handleEdit}
          >
            <Text style={styles.buttonText}>✏️ Edit Produk</Text>
          </TouchableOpacity>

          <TouchableOpacity 
            style={[styles.button, styles.deleteButton]}
            onPress={handleDelete}
          >
            <Text style={styles.buttonText}>🗑️ Hapus Produk</Text>
          </TouchableOpacity>
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
  imageContainer: {
    alignItems: 'center',
    backgroundColor: '#f5f5f5',
    paddingVertical: 40,
  },
  image: {
    fontSize: 120,
  },
  contentContainer: {
    padding: 20,
  },
  name: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 20,
  },
  infoRow: {
    flexDirection: 'row',
    marginBottom: 12,
  },
  label: {
    fontSize: 16,
    fontWeight: '600',
    color: '#666',
    width: 120,
  },
  value: {
    fontSize: 16,
    color: '#333',
    flex: 1,
  },
  price: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#e74c3c',
    flex: 1,
  },
  descriptionContainer: {
    marginTop: 20,
    marginBottom: 30,
  },
  description: {
    fontSize: 16,
    color: '#555',
    lineHeight: 24,
    marginTop: 8,
  },
  actionButtons: {
    gap: 12,
  },
  button: {
    paddingVertical: 14,
    paddingHorizontal: 20,
    borderRadius: 8,
    alignItems: 'center',
  },
  editButton: {
    backgroundColor: '#3498db',
  },
  deleteButton: {
    backgroundColor: '#e74c3c',
  },
  buttonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  errorText: {
    fontSize: 16,
    color: '#999',
    textAlign: 'center',
    marginTop: 50,
  },
});
