// screens/HomeScreen.js
import React from 'react';
import { 
  View, 
  Text, 
  FlatList, 
  StyleSheet, 
  TouchableOpacity,
  Alert 
} from 'react-native';
import { ProductCard } from '../components/ProductCard';
import { useProducts } from '../context/ProductContext';

export default function HomeScreen({ navigation }) {
  // Ambil data dan fungsi dari Context
  const { products, deleteProduct } = useProducts();

  // Render item menggunakan ProductCard
  const renderProduct = ({ item }) => (
    <ProductCard 
      product={item} 
      onPress={() => navigation.navigate('Detail', { productId: item.id })}
    />
  );

  return (
    <View style={styles.container}>
      {/* Header dengan tombol aksi */}
      <View style={styles.headerButtons}>
        <TouchableOpacity 
          style={styles.addButton}
          onPress={() => navigation.navigate('Form', { mode: 'add' })}
        >
          <Text style={styles.buttonText}>➕ Tambah Produk</Text>
        </TouchableOpacity>
        
        <TouchableOpacity 
          style={styles.aboutButton}
          onPress={() => navigation.navigate('About')}
        >
          <Text style={styles.buttonText}>ℹ️ About</Text>
        </TouchableOpacity>
      </View>

      {/* Tampilkan jumlah produk */}
      <View style={styles.countContainer}>
        <Text style={styles.countText}>
          Total Produk: {products.length}
        </Text>
      </View>

      {/* FlatList untuk menampilkan produk */}
      <FlatList
        data={products}
        renderItem={renderProduct}
        keyExtractor={(item) => item.id}
        contentContainerStyle={styles.listContainer}
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Text style={styles.emptyEmoji}>🍽️</Text>
            <Text style={styles.emptyText}>Belum ada produk</Text>
            <Text style={styles.emptySubtext}>Klik "Tambah Produk" untuk mulai</Text>
          </View>
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  headerButtons: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    padding: 10,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#ddd',
  },
  addButton: {
    backgroundColor: '#27ae60',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 8,
  },
  aboutButton: {
    backgroundColor: '#3498db',
    paddingVertical: 10,
    paddingHorizontal: 20,
    borderRadius: 8,
  },
  buttonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 14,
  },
  countContainer: {
    padding: 10,
    backgroundColor: '#fff',
    alignItems: 'center',
  },
  countText: {
    fontSize: 14,
    color: '#666',
    fontWeight: '600',
  },
  listContainer: {
    paddingVertical: 10,
  },
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 80,
  },
  emptyEmoji: {
    fontSize: 80,
    marginBottom: 16,
  },
  emptyText: {
    fontSize: 18,
    color: '#666',
    fontWeight: '600',
    marginBottom: 8,
  },
  emptySubtext: {
    fontSize: 14,
    color: '#999',
  },
});
