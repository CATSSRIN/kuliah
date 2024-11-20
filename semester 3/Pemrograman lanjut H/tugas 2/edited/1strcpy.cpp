#include <stdio.h>
#include <stdlib.h>   // For exit() function
#include <string.h>   // For strcmp() and strncpy()

int main() {
  char fname[81];
  char prevline[101] = "";
  char buffer[101];
  FILE *instream;

  printf("Check which file: ");
  scanf("%80s", fname);

  if ((instream = fopen(fname, "r")) == NULL) {
    printf("Unable to open file %s\n", fname);
    exit(-1);
  }

  // Read a line of characters from the file
  while (fgets(buffer, sizeof(buffer), instream) != NULL) {
    // If current line is the same as previous
    if (!strcmp(buffer, prevline)) {
      printf("Duplicate line: %s", buffer);
    }
    // If the first 10 characters of both lines are the same
    else if (!strncmp(buffer, prevline, 10)) {
      printf("Start the same:\n  %s  %s", prevline, buffer);
    }

    // Copy the current line to the previous line
    strcpy(prevline, buffer);
  }

  fclose(instream);
  return 0;  // Indicate successful execution
}
