"""
Tugas 1: Text Preprocessing Pipeline
Course: Information Retrieval
Author: caezarlov

Features Implemented:
1. Load Data Text
2. Tokenization
3. Normalization (Case folding, Regex cleaning, Slang/Contraction dictionary lookup)
4. Stop Word Removal (NLTK + Custom Stopwords dataset)
5. Stemming (Porter Stemmer / Snowball Stemmer)
6. Lemmatization (WordNet Lemmatizer with POS Tagging)
"""

import os
import re
import string
import pandas as pd
import nltk
from tabulate import tabulate

# Ensure necessary NLTK datasets are downloaded
def download_nltk_resources():
    resources = [
        ('tokenizers/punkt', 'punkt'),
        ('tokenizers/punkt_tab', 'punkt_tab'),
        ('corpora/stopwords', 'stopwords'),
        ('corpora/wordnet', 'wordnet'),
        ('corpora/omw-1.4', 'omw-1.4'),
        ('taggers/averaged_perceptron_tagger', 'averaged_perceptron_tagger'),
        ('taggers/averaged_perceptron_tagger_eng', 'averaged_perceptron_tagger_eng')
    ]
    for res_path, res_name in resources:
        try:
            nltk.data.find(res_path)
        except LookupError:
            print(f"[Setup] Downloading NLTK resource: {res_name}...")
            nltk.download(res_name, quiet=True)

download_nltk_resources()

from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords, wordnet
from nltk.stem import PorterStemmer, SnowballStemmer, WordNetLemmatizer
from nltk import pos_tag


class TextPreprocessor:
    def __init__(self, normalization_dict_path=None, stopwords_path=None):
        """
        Initialize TextPreprocessor with optional custom normalization and stopword datasets.
        """
        self.porter_stemmer = PorterStemmer()
        self.snowball_stemmer = SnowballStemmer("english")
        self.lemmatizer = WordNetLemmatizer()
        
        # Load normalization dictionary
        self.normalization_dict = {}
        if normalization_dict_path and os.path.exists(normalization_dict_path):
            self.load_normalization_dict(normalization_dict_path)
        else:
            default_norm_path = os.path.join(os.path.dirname(__file__), "dataset", "normalization_dict.csv")
            if os.path.exists(default_norm_path):
                self.load_normalization_dict(default_norm_path)
                
        # Load custom stopwords
        self.custom_stopwords = set()
        if stopwords_path and os.path.exists(stopwords_path):
            self.load_custom_stopwords(stopwords_path)
        else:
            default_stop_path = os.path.join(os.path.dirname(__file__), "dataset", "stopwords.txt")
            if os.path.exists(default_stop_path):
                self.load_custom_stopwords(default_stop_path)
                
        # Combined stopwords set (NLTK english + custom)
        try:
            nltk_stops = set(stopwords.words('english'))
        except Exception:
            nltk_stops = set()
        self.all_stopwords = nltk_stops.union(self.custom_stopwords)

    def load_normalization_dict(self, filepath):
        """Loads slang/informal words to formal words dictionary from CSV."""
        df = pd.read_csv(filepath)
        # Expected columns: slang, formal
        if 'slang' in df.columns and 'formal' in df.columns:
            self.normalization_dict = dict(zip(df['slang'].astype(str).str.lower(), df['formal'].astype(str).str.lower()))
            print(f"[Dataset] Loaded {len(self.normalization_dict)} normalization mappings from {filepath}")
        else:
            print(f"[Warning] Normalization CSV does not have 'slang' and 'formal' columns.")

    def load_custom_stopwords(self, filepath):
        """Loads custom stopwords from text or CSV file."""
        if filepath.endswith('.csv'):
            df = pd.read_csv(filepath)
            col = df.columns[0]
            self.custom_stopwords = set(df[col].dropna().astype(str).str.strip().str.lower())
        else:
            with open(filepath, 'r', encoding='utf-8') as f:
                self.custom_stopwords = set(line.strip().lower() for line in f if line.strip())
        print(f"[Dataset] Loaded {len(self.custom_stopwords)} custom stopwords from {filepath}")

    # ================= 1. LOAD DATA =================
    @staticmethod
    def load_data(filepath, text_column="Comment", sample_size=None):
        """
        Load text dataset from CSV file.
        """
        if not os.path.exists(filepath):
            raise FileNotFoundError(f"Dataset file not found at: {filepath}")
        
        df = pd.read_csv(filepath)
        print(f"[Data] Successfully loaded dataset: {filepath}")
        print(f"[Data] Total records: {len(df):,}")
        print(f"[Data] Available columns: {list(df.columns)}")
        
        # Clean null values in target text column
        if text_column in df.columns:
            df = df.dropna(subset=[text_column])
            df[text_column] = df[text_column].astype(str)
        else:
            # Fallback to first text column
            first_col = df.columns[0]
            print(f"[Notice] Column '{text_column}' not found. Using '{first_col}' instead.")
            df = df.dropna(subset=[first_col])
            df[first_col] = df[first_col].astype(str)
            text_column = first_col

        if sample_size and sample_size < len(df):
            df = df.head(sample_size).copy()
            print(f"[Data] Running on subset of {sample_size} records.")

        return df, text_column

    # ================= 2. NORMALIZATION =================
    def normalize(self, text, remove_numbers=False):
        """
        Performs text normalization:
        1. Lowercase (case folding)
        2. Remove URLs, HTML tags, and mentions/hashtags
        3. Remove non-ASCII characters / emojis
        4. Normalize slang words / contractions using normalization dataset
        5. Remove punctuation and extra whitespace
        """
        if not isinstance(text, str):
            text = str(text)

        # 1. Case Folding
        text = text.lower()

        # 2. Remove URLs
        text = re.sub(r'https?://\S+|www\.\S+', ' ', text)
        
        # 3. Remove HTML tags
        text = re.sub(r'<.*?>', ' ', text)

        # 4. Remove Emojis and non-ASCII characters
        text = text.encode('ascii', 'ignore').decode('utf-8')

        # 5. Remove standard punctuation and clean special quotes
        text = text.replace("’", "'").replace("‘", "'").replace("“", '"').replace("”", '"')
        
        # 6. Slang & Contraction Normalization Lookup
        # Match words and replace if found in dictionary
        words = text.split()
        normalized_words = [self.normalization_dict.get(w, w) for w in words]
        text = " ".join(normalized_words)

        # 7. Remove remaining punctuation (keep alphanumeric and spaces)
        text = text.translate(str.maketrans(string.punctuation, ' ' * len(string.punctuation)))

        # 8. Optional: Remove numbers
        if remove_numbers:
            text = re.sub(r'\d+', ' ', text)

        # 9. Clean extra whitespace
        text = re.sub(r'\s+', ' ', text).strip()

        return text

    # ================= 3. TOKENIZATION =================
    @staticmethod
    def tokenize(text, method='nltk'):
        """
        Splits normalized text into a list of word tokens.
        Methods: 'nltk' (word_tokenize) or 'whitespace' or 'regex'
        """
        if not text:
            return []
        
        if method == 'nltk':
            try:
                return word_tokenize(text)
            except Exception:
                return text.split()
        elif method == 'regex':
            return re.findall(r'\b\w+\b', text)
        else:
            return text.split()

    # ================= 4. STOP WORD REMOVAL =================
    def remove_stopwords(self, tokens, custom_stop_set=None):
        """
        Filters out stopwords from a list of tokens.
        """
        active_stops = custom_stop_set if custom_stop_set is not None else self.all_stopwords
        return [word for word in tokens if word not in active_stops and len(word) > 1]

    # ================= 5. STEMMING =================
    def stem_tokens(self, tokens, algorithm='porter'):
        """
        Reduces tokens to their root/stem using Porter or Snowball stemmer.
        """
        if algorithm.lower() == 'snowball':
            return [self.snowball_stemmer.stem(word) for word in tokens]
        else:
            return [self.porter_stemmer.stem(word) for word in tokens]

    # ================= 6. LEMMATIZATION =================
    @staticmethod
    def _get_wordnet_pos(treebank_tag):
        """
        Maps NLTK Treebank POS tag to WordNet POS tag for accurate lemmatization.
        """
        if treebank_tag.startswith('J'):
            return wordnet.ADJ
        elif treebank_tag.startswith('V'):
            return wordnet.VERB
        elif treebank_tag.startswith('N'):
            return wordnet.NOUN
        elif treebank_tag.startswith('R'):
            return wordnet.ADV
        else:
            return wordnet.NOUN

    def lemmatize_tokens(self, tokens, use_pos_tagging=True):
        """
        Lemmatizes tokens to their canonical dictionary base form.
        Uses POS tagging for high-accuracy lemmatization (e.g. 'running' (v) -> 'run').
        """
        if not tokens:
            return []
        
        if use_pos_tagging:
            try:
                pos_tags = pos_tag(tokens)
                return [
                    self.lemmatizer.lemmatize(word, self._get_wordnet_pos(tag))
                    for word, tag in pos_tags
                ]
            except Exception:
                return [self.lemmatizer.lemmatize(word) for word in tokens]
        else:
            return [self.lemmatizer.lemmatize(word) for word in tokens]

    # ================= FULL PIPELINE =================
    def process_text(self, text, stem_algo='porter', use_pos_lemma=True):
        """
        Executes the entire end-to-end preprocessing pipeline on a single text string.
        Returns a dictionary containing intermediate and final representations.
        """
        normalized = self.normalize(text)
        tokens = self.tokenize(normalized)
        no_stopwords = self.remove_stopwords(tokens)
        stemmed = self.stem_tokens(no_stopwords, algorithm=stem_algo)
        lemmatized = self.lemmatize_tokens(no_stopwords, use_pos_tagging=use_pos_lemma)
        
        return {
            'original': text,
            'normalized': normalized,
            'tokens': tokens,
            'stopwords_removed': no_stopwords,
            'stemmed': stemmed,
            'lemmatized': lemmatized,
            'final_text': " ".join(lemmatized)
        }

    def process_dataframe(self, df, text_column='Comment'):
        """
        Applies preprocessing across an entire Pandas DataFrame.
        """
        print("[Pipeline] Processing DataFrame across all 6 stages...")
        results = []
        for text in df[text_column]:
            results.append(self.process_text(text))
            
        processed_df = df.copy()
        processed_df['normalized'] = [r['normalized'] for r in results]
        processed_df['tokens'] = [r['tokens'] for r in results]
        processed_df['stopwords_removed'] = [r['stopwords_removed'] for r in results]
        processed_df['stemmed'] = [r['stemmed'] for r in results]
        processed_df['lemmatized'] = [r['lemmatized'] for r in results]
        processed_df['final_preprocessed_text'] = [r['final_text'] for r in results]
        
        print("[Pipeline] Preprocessing completed successfully.")
        return processed_df


def main():
    print("=" * 70)
    print(" TUGAS 1 - INFORMATION RETRIEVAL: TEXT PREPROCESSING PIPELINE ")
    print("=" * 70)

    base_dir = os.path.dirname(os.path.abspath(__file__))
    dataset_csv = os.path.join(base_dir, "dataset", "YoutubeCommentsDataSet.csv")
    norm_csv = os.path.join(base_dir, "dataset", "normalization_dict.csv")
    stop_txt = os.path.join(base_dir, "dataset", "stopwords.txt")
    output_csv = os.path.join(base_dir, "dataset", "processed_youtube_comments.csv")

    # Initialize Preprocessor
    preprocessor = TextPreprocessor(
        normalization_dict_path=norm_csv,
        stopwords_path=stop_txt
    )

    # 1. Load Data (Process only a small sample of records, e.g. 5 comments)
    SAMPLE_LIMIT = 5
    df, text_col = preprocessor.load_data(dataset_csv, text_column="Comment", sample_size=SAMPLE_LIMIT)

    # 2 - 6. Execute Pipeline
    processed_df = preprocessor.process_dataframe(df, text_column=text_col)

    # Prepare Explanatory Table for TXT output
    table_rows = []
    for idx, row in processed_df.iterrows():
        sample_num = f"Sample {idx + 1}"
        orig = str(row[text_col])
        # Format tokens as comma-separated strings for readable table presentation
        norm = str(row['normalized'])
        toks = ", ".join(row['tokens'])
        stops = ", ".join(row['stopwords_removed'])
        stem = ", ".join(row['stemmed'])
        lemma = ", ".join(row['lemmatized'])
        
        table_rows.append([
            sample_num,
            orig,
            norm,
            toks,
            stops,
            stem,
            lemma
        ])

    table_headers = [
        "Sample",
        "Original Text\n(Raw Input)",
        "1. Normalization\n(Lowercased, Slang Replaced,\nPunctuation/URLs Removed)",
        "2. Tokenization\n(Split into Individual\nWord Tokens)",
        "3. Stopword Removal\n(Filtered Noise &\nCommon Non-Informative Words)",
        "4. Stemming\n(Porter Stemmer Root\nAffix Chopping)",
        "5. Lemmatization\n(WordNet POS-Aware\nDictionary Base Form)"
    ]

    # Generate formatted ASCII Grid Table
    formatted_table = tabulate(table_rows, headers=table_headers, tablefmt="grid", maxcolwidths=[10, 25, 25, 25, 25, 25, 25])

    # Build full explanatory text report
    report_content = f"""========================================================================================================================
                      INFORMATION RETRIEVAL (TUGAS 1) - TEXT PREPROCESSING PIPELINE REPORT
========================================================================================================================
Dataset Source : YoutubeCommentsDataSet.csv (Sample subset: {SAMPLE_LIMIT} comments)
Dictionaries   : normalization_dict.csv (Slang/Contractions), stopwords.txt (Custom + NLTK Stopwords)
Generated At   : Automated Pipeline Execution
========================================================================================================================

COLUMN DEFINITIONS & PREPROCESSING EXPLANATIONS:
------------------------------------------------------------------------------------------------------------------------
1. [Original Text]
   - Raw, unprocessed comment text directly loaded from the dataset.

2. [Normalization]
   - Transformasi teks ke bentuk standar:
     * Case Folding: Mengubah semua karakter menjadi huruf kecil (lowercase).
     * Noise Removal: Menghapus URL, tag HTML, emoji, dan karakter non-ASCII.
     * Slang & Contraction Replacement: Mengganti kata tidak baku / singkatan menggunakan kamus normalisasi
       (contoh: 'u' -> 'you', 'dont' -> 'do not', 'psu' -> 'power supply unit', 'lmg' -> 'linus media group').
     * Punctuation Removal: Menghapus simbol dan tanda baca yang tidak esensial.

3. [Tokenization]
   - Pemecahan string kalimat yang telah dinormalisasi menjadi unit-unit token kata individual menggunakan NLTK word_tokenize.

4. [Stopword Removal]
   - Pemfilteran kata-kata umum (seperti 'the', 'is', 'in', 'at', 'which') serta noise spesifik YouTube/media sosial
     ('video', 'channel', 'subscribe', 'like') yang tidak memiliki bobot informasi pembeda dalam Information Retrieval.

5. [Stemming]
   - Pemotongan afiks/imbuhan kata secara heuristik menggunakan algoritma Porter Stemmer untuk menghasilkan akar kata
     (contoh: 'retailers' -> 'retail', 'required' -> 'requir').

6. [Lemmatization]
   - Pengembalian kata ke bentuk lema kamus yang tepat secara morfologis dengan mempertimbangkan kelas kata (Part-of-Speech / POS Tagging)
     menggunakan WordNet Lemmatizer (contoh: 'studies' -> 'study', 'better' -> 'good/well', 'went' -> 'go').
========================================================================================================================

TABULAR RESULTS FOR PROCESSED SAMPLES:
------------------------------------------------------------------------------------------------------------------------
{formatted_table}

========================================================================================================================
STEMMING VS LEMMATIZATION COMPARISON MATRIX:
------------------------------------------------------------------------------------------------------------------------
"""
    sample_words = ["running", "studies", "better", "went", "universities", "bought", "contactless", "retailers", "remorse"]
    comp_rows = []
    for word in sample_words:
        stemmed_w = preprocessor.porter_stemmer.stem(word)
        pos = TextPreprocessor._get_wordnet_pos(pos_tag([word])[0][1])
        lemma_w = preprocessor.lemmatizer.lemmatize(word, pos)
        comp_rows.append([word, stemmed_w, lemma_w, pos])

    comp_table = tabulate(comp_rows, headers=["Original Word", "Porter Stemmer", "POS Lemmatizer", "POS Tag"], tablefmt="grid")
    report_content += comp_table + "\n\n========================================================================================================================\n"

    # Save to TXT file
    output_txt = os.path.join(base_dir, "preprocessing_results_table.txt")
    with open(output_txt, "w", encoding="utf-8") as f:
        f.write(report_content)

    print("\n" + formatted_table)
    print(f"\n[Export] Detailed explanatory table successfully written to: {output_txt}")


if __name__ == "__main__":
    main()
