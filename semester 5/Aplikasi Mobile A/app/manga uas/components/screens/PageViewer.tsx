import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, Dimensions, TouchableOpacity, Image, Alert } from 'react-native';
import * as FileSystem from 'expo-file-system/legacy';
import ImageZoom from 'react-native-image-pan-zoom';
import { RouteProp, useNavigation } from '@react-navigation/native';
import { SafeAreaView, useSafeAreaInsets } from 'react-native-safe-area-context';
import AsyncStorage from '@react-native-async-storage/async-storage';

type RootStackParamList = {
  PageViewer: {
    pages?: string[];
    startIndex?: number;
    mangaId?: string;
    chapterId?: string;
    title?: string;
    cover?: string;
  };
  OfflineSaveScreen: {
    pages?: string[];
    mangaId?: string;
    chapterId?: string;
    title?: string;
    cover?: string;
  };
  BookmarkScreen: {
    pages?: string[];
    mangaId?: string;
    chapterId?: string;
    title?: string;
    cover?: string;
  };
};

type PageViewerRouteProp = RouteProp<RootStackParamList, 'PageViewer'>;

type Props = {
  route: PageViewerRouteProp;
};

const { width, height } = Dimensions.get('window');

export default function PageViewer({ route }: Props) {
  const { pages = [], startIndex = 0, mangaId, chapterId, title, cover } = route.params || {};
  const [currentIndex, setCurrentIndex] = useState(startIndex);
  const insets = useSafeAreaInsets();
  const navigation = useNavigation<any>();

  // ✅ auto-save ke history setiap kali currentIndex berubah
  useEffect(() => {
    const saveHistory = async () => {
      try {
        const historyItem = {
          mangaId,
          chapterId,
          title: title ?? 'Untitled',
          cover: cover || pages[0],
          pages,
          lastIndex: currentIndex,
          updatedAt: Date.now(),
        };

        const existing = await AsyncStorage.getItem('history');
        const history = existing ? JSON.parse(existing) : [];

        const updated = history.filter(
          (h: any) => !(h.mangaId === mangaId && h.chapterId === chapterId)
        );
        updated.push(historyItem);

        await AsyncStorage.setItem('history', JSON.stringify(updated));
      } catch (err) {
        console.log('Error saving history', err);
      }
    };

    if (pages.length > 0) {
      saveHistory();
    }
  }, [currentIndex]);

  const goPrev = () => {
    if (currentIndex > 0) setCurrentIndex(currentIndex - 1);
  };
  const goNext = () => {
    if (currentIndex < pages.length - 1) setCurrentIndex(currentIndex + 1);
  };

  // ✅ navigasi ke OfflineSaveScreen
  const saveOffline = () => {
    navigation.navigate('OfflineSaveScreen', {
      pages,
      mangaId,
      chapterId,
      title,
      cover,
    });
  };

  // ✅ navigasi ke BookmarkScreen
  const addBookmark = () => {
    navigation.navigate('BookmarkSaveScreen', {
      pages,
      mangaId,
      chapterId,
      title,
      cover,
    });
  };

  if (!pages || pages.length === 0) {
    return (
      <SafeAreaView style={styles.safeArea} edges={['top','bottom']}>
        <View style={styles.center}>
          <Text style={{ color: '#fff' }}>No pages available</Text>
        </View>
      </SafeAreaView>
    );
  }

  return (
    <SafeAreaView style={styles.safeArea} edges={['top','bottom']}>
      <View style={[styles.container, { paddingBottom: insets.bottom }]}>
        <Text style={styles.pageTitle}>
          {title ?? 'Untitled'} — Page {currentIndex + 1} / {pages.length}
        </Text>

        <View style={styles.imageWrapper} pointerEvents="box-none">
          {/* @ts-ignore */}
          <ImageZoom
            cropWidth={width}
            cropHeight={height - insets.bottom - 100}
            imageWidth={width}
            imageHeight={height}
          >
            <Image
              source={{ uri: pages[currentIndex] }}
              style={{ width, height, resizeMode: 'contain' }}
            />
          </ImageZoom>
        </View>

        {/* ✅ Menu di bawah */}
        <View style={styles.bottomControls}>
          <TouchableOpacity style={styles.navButton} onPress={goPrev} disabled={currentIndex === 0}>
            <Text style={styles.navText}>◀ Prev</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.navButton} onPress={saveOffline}>
            <Text style={styles.navText}>💾 Save Offline</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.navButton} onPress={addBookmark}>
            <Text style={styles.navText}>⭐ Bookmark</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.navButton}
            onPress={goNext}
            disabled={currentIndex === pages.length - 1}
          >
            <Text style={styles.navText}>Next ▶</Text>
          </TouchableOpacity>
        </View>
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: '#000' },
  container: { flex: 1 },
  pageTitle: { color: '#fff', fontSize: 16, margin: 12, textAlign: 'center' },
  bottomControls: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    paddingVertical: 8,
    backgroundColor: '#111',
  },
  navButton: {
    paddingVertical: 6,
    paddingHorizontal: 12,
    backgroundColor: '#a78bfa',
    borderRadius: 6,
  },
  navText: { color: '#fff', fontWeight: 'bold' },
  center: { flex: 1, justifyContent: 'center', alignItems: 'center' },
  imageWrapper: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
});
