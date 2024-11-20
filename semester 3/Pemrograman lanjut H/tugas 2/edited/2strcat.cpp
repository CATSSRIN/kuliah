#include <stdio.h>
#include <stdlib.h>  // For exit() function
#include <string.h>  // For strcpy(), strcat(), strncat(), etc.

int main() {
  char fname[81];
  char buffer[101];
  char curraddress[201] = "";
  FILE *instream;
  int first = 1;

  printf("Address file: ");
  scanf("%80s", fname);

  if ((instream = fopen(fname, "r")) == NULL) {
    printf("Unable to open file %s\n", fname);
    exit(-1);
  }

  // Read lines from the file
  while (fgets(buffer, sizeof(buffer), instream) != NULL) {
    if (buffer[0] == '*') {  // End of address
      printf("%s\n", curraddress);  // Print the address
      curraddress[0] = '\0';  // Reset the address string
      first = 1;  // Reset the first entry flag
    } else {
      // Add a comma if this is not the first line of the address
      if (!first) {
        strcat(curraddress, ", ");
      } else {
        first = 0;
      }

      // Remove the trailing newline (if present)
      buffer[strcspn(buffer, "\n")] = '\0';

      // Add the current line to the address
      strcat(curraddress, buffer);
    }
  }

  fclose(instream);
  return 0;
}
