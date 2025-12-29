declare module 'expo-file-system' {
  export class File {
    constructor(directory: any, name: string);
    existsAsync(): Promise<boolean>;
    readAsStringAsync(): Promise<string>;
    writeAsStringAsync(data: string): Promise<void>;
    deleteAsync(): Promise<void>;
  }

  export class Directory {
    static document: any;
    static cache: any;
  }
}
