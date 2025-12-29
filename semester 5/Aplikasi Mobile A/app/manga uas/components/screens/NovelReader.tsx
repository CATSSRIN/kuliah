import React, { useEffect, useState } from 'react';
import { View, Text, ScrollView, StyleSheet, ActivityIndicator } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import config from '../../utils/config';

export default function NovelReader({ route }: any) {
  const { novel } = route.params;
  const [authorName, setAuthorName] = useState<string>('Unknown');
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    const fetchAuthor = async () => {
      try {
        const storedUsername = await AsyncStorage.getItem('username');
        const username = storedUsername ?? 'adiel'; // fallback untuk test

        const res = await fetch(`${config.PROFILE}?username=${encodeURIComponent(username)}`);
        const data = await res.json();

        if (data.success) {
          setAuthorName(data.username);
        } else {
          setAuthorName('Unknown');
        }
      } catch (err) {
        setAuthorName('Unknown');
      } finally {
        setLoading(false);
      }
    };

    fetchAuthor();
  }, []);

  if (!novel) {
    return (
      <View style={styles.center}>
        <Text style={{ color: 'red' }}>Novel tidak ditemukan</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.title}>{novel.title}</Text>
      {loading ? (
        <ActivityIndicator color="#a78bfa" />
      ) : (
        <Text style={styles.author}>Author: {authorName}</Text>
      )}
      <Text style={styles.content}>{novel.content || 'Tidak ada konten tersedia.'}</Text>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#1e1b2e', padding: 16 },
  title: { color: '#fff', fontSize: 20, fontWeight: 'bold', marginBottom: 8 },
  author: { color: '#aaa', fontSize: 14, marginBottom: 12 },
  content: { color: '#fff', fontSize: 16, lineHeight: 24 },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#1e1b2e' },
});
