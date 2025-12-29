import React, { useState } from 'react';
import { View, Text, StyleSheet, Dimensions, TouchableOpacity, Image } from 'react-native';
import * as FileSystem from 'expo-file-system/legacy';
import ImageZoom from 'react-native-image-pan-zoom';
import { RouteProp } from '@react-navigation/native';
import { SafeAreaView, useSafeAreaInsets } from 'react-native-safe-area-context';

type RootStackParamList = {
  PageViewer: { pages: string[]; startIndex: number; mangaId?: string; chapterId?: string };
};

type PageViewerRouteProp = RouteProp<RootStackParamList, 'PageViewer'>;

type Props = {
  route: PageViewerRouteProp;
};

const { width, height } = Dimensions.get('window');

export default function PageViewer({ route }: Props) {
  const { pages = [], startIndex = 0, mangaId, chapterId } = route.params;
  const [currentIndex, setCurrentIndex] = useState(startIndex);
  const insets = useSafeAreaInsets();

  const goPrev = () => {
    if (currentIndex > 0) setCurrentIndex(currentIndex - 1);
  };
  const goNext = () => {
    if (currentIndex < pages.length - 1) setCurrentIndex(currentIndex + 1);
  };

  const saveOffline = async () => {
    try {
      const mangaKey = mangaId || 'unknownManga';
      const chapterKey = chapterId || 'unknownChapter';

      // ✅ bikin folder unik pakai timestamp
      const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
      const dir =
        (FileSystem as any).documentDirectory +
        `manga-${mangaKey}-chapter-${chapterKey}-${timestamp}/`;

      await FileSystem.makeDirectoryAsync(dir, { intermediates: true });

      for (let i = 0; i < pages.length; i++) {
        const url = pages[i];
        const localPath = dir + `page-${i}.jpg`;
        await FileSystem.downloadAsync(url, localPath);
      }

      alert(`Chapter ${chapterKey} dari Manga ${mangaKey} berhasil disimpan offline (${timestamp})!`);
    } catch (err) {
      console.error(err);
      alert('Failed to save offline');
    }
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
          Page {currentIndex + 1} / {pages.length}
        </Text>

        {/* Tombol di atas layar */}
        <View style={styles.topControls}>
          <TouchableOpacity style={styles.navButton} onPress={goPrev} disabled={currentIndex === 0}>
            <Text style={styles.navText}>◀ Prev</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.navButton} onPress={saveOffline}>
            <Text style={styles.navText}>💾 Save Offline</Text>
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.navButton}
            onPress={goNext}
            disabled={currentIndex === pages.length - 1}
          >
            <Text style={styles.navText}>Next ▶</Text>
          </TouchableOpacity>
        </View>

        {/* Zoom area dengan pointerEvents box-none */}
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
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: '#000' },
  container: { flex: 1 },
  pageTitle: { color: '#fff', fontSize: 16, margin: 12, textAlign: 'center' },
  topControls: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    paddingVertical: 8,
    backgroundColor: '#111',
    zIndex: 10,
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
