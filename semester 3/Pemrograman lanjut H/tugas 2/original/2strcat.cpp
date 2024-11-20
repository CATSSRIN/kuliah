#include <stdio.h>
#include <string.h>

void main() {
  char fname[81];
  char buffer[101];
  char curraddress[201] = "";
  FILE *instream;
  int first = 1;

  printf("Address file: ");
  scanf("%80s",fname);

  if ((instream = fopen(fname,"r")) == NULL) {
    printf("Unable to open file %s\n",fname);
    exit(-1);
  }
/* Read a line */
while (fgets(buffer,sizeof(buffer)-1,instream) != NULL) {
    if (buffer[0] == '*') { /* End of address */
      printf("%s\n",curraddress); /* Print address */
      strcpy(curraddress,""); /* Reset address to “” */
      first = 1;
    }
    else {
      /* Add comma (if not first entry in address) */
      if (first) first = 0; else strcat(curraddress,", ");
      /* Add line (minus newline) to address */
      strncat(curraddress,buffer,strlen(buffer)-1);
    }
  }

  fclose(instream);
}
