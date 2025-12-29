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

interface BookmarkItem {
  title: string;
  savedAt: number;
}

export default function BookmarkScreen() {
  const [bookmarks, setBookmarks] = useState<BookmarkItem[]>([]);
  const navigation = useNavigation<any>(); // pakai any biar aman

  useEffect(() => {
    const loadBookmarks = async () => {
      try {
        const keys = await AsyncStorage.getAllKeys();
        const bookmarkKeys = keys.filter((k) => k.startsWith('bookmark:'));
        const entries = await AsyncStorage.multiGet(bookmarkKeys);

        const parsed: BookmarkItem[] = entries.map(([key, value]) => {
          const data = value ? JSON.parse(value) : null;
          return {
            title: data?.title || key.replace('bookmark:', ''),
            savedAt: data?.savedAt || Date.now(),
          };
        });
        setBookmarks(parsed);
      } catch (e) {
        console.log('Error loading bookmarks', e);
      }
    };

    loadBookmarks();
  }, []);

  const renderItem = ({ item }: { item: BookmarkItem }) => (
    <TouchableOpacity
      style={styles.item}
      onPress={() => navigation.navigate('MangaReader', { title: item.title })}
    >
      <Text style={styles.title}>{item.title}</Text>
      <Text style={styles.date}>
        {new Date(item.savedAt).toLocaleDateString()}
      </Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {bookmarks.length === 0 ? (
        <Text style={styles.empty}>No bookmarks yet</Text>
      ) : (
        <FlatList
          data={bookmarks}
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
  date: { color: '#aaa', fontSize: 12, marginTop: 4 },
  empty: { color: '#888', textAlign: 'center', marginTop: 32 },
});
