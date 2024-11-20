#include <stdio.h>
#include <stdlib.h>  // For exit() function
#include <string.h>  // For strstr(), strncpy(), etc.

int main() {
  char fname[81];     
  char buffer[101];   
  char noncom[101];   
  FILE *instream;     
  char *loc;          

  printf("File: ");
  scanf("%80s", fname);

  // Open the file for reading
  if ((instream = fopen(fname, "r")) == NULL) {
    printf("Unable to open file %s\n", fname);
    exit(-1);
  }

  // Read each line from the file
  while (fgets(buffer, sizeof(buffer), instream) != NULL) {
    // Look for the backslash '\' in the line
    if ((loc = strstr(buffer, "\\")) != NULL) {
      // Copy the characters before the backslash to noncom
      strncpy(noncom, buffer, loc - buffer);
      // Add null-terminator to mark end of string
      noncom[loc - buffer] = '\0';
      // Print the part before the backslash
      printf("%s\n", noncom);
    } else {
      // If no backslash found, print the line as is
      printf("%s", buffer);
    }
  }

  // Close the file
  fclose(instream);
  return 0;
}
