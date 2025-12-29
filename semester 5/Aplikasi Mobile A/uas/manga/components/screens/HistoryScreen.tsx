import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  FlatList,
  TouchableOpacity,
  StyleSheet,
} from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useNavigation } from '@react-navigation/native';

interface HistoryItem {
  title: string;
  lastIndex: number;
  updatedAt: number;
}

export default function HistoryScreen() {
  const [history, setHistory] = useState<HistoryItem[]>([]);
  const navigation = useNavigation<any>();

  useEffect(() => {
    const loadHistory = async () => {
      try {
        const keys = await AsyncStorage.getAllKeys();
        const historyKeys = keys.filter((k) => k.startsWith('history:'));
        const entries = await AsyncStorage.multiGet(historyKeys);

        const parsed: HistoryItem[] = entries.map(([key, value]) => {
          const data = value ? JSON.parse(value) : null;
          return {
            title: data?.title || key.replace('history:', ''),
            lastIndex: data?.lastIndex || 0,
            updatedAt: data?.updatedAt || Date.now(),
          };
        });
        setHistory(parsed);
      } catch (e) {
        console.log('Error loading history', e);
      }
    };

    loadHistory();
  }, []);

  const renderItem = ({ item }: { item: HistoryItem }) => (
    <TouchableOpacity
      style={styles.item}
      onPress={() =>
        navigation.navigate('MangaReader', {
          title: item.title,
          // bisa tambahin param lain kalau mau langsung ke halaman terakhir
        })
      }
    >
      <Text style={styles.title}>{item.title}</Text>
      <Text style={styles.detail}>
        Last page: {item.lastIndex + 1}
      </Text>
      <Text style={styles.date}>
        {new Date(item.updatedAt).toLocaleString()}
      </Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {history.length === 0 ? (
        <Text style={styles.empty}>No history yet</Text>
      ) : (
        <FlatList
          data={history}
          keyExtractor={(item) => item.title}
          renderItem={renderItem}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#0f0e19', padding: 16 },
  item: {
    padding: 16,
    backgroundColor: '#1e1b2e',
    marginBottom: 12,
    borderRadius: 8,
  },
  title: { color: '#fff', fontSize: 16, fontWeight: '600' },
  detail: { color: '#ccc', fontSize: 14, marginTop: 4 },
  date: { color: '#aaa', fontSize: 12, marginTop: 2 },
  empty: { color: '#888', textAlign: 'center', marginTop: 32 },
});
