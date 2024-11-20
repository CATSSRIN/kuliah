#include <stdio.h>
#include <string.h>

void main() {
  char fname[81];
  char prevline[101] = "";
  char buffer[101];
  FILE *instream;

  printf("Check which file: ");
  scanf("%80s",fname);

  if ((instream = fopen(fname,"r")) == NULL) {
    printf("Unable to open file %s\n",fname);
    exit(-1);
  }
  /* read a line of characters */
  while (fgets(buffer,sizeof(buffer)-1,instream) != NULL) {
    /* if current line same as previous */
    if (!strcmp(buffer,prevline))
      printf("Duplicate line: %s",buffer);
    /* otherwise if the first 10 characters of the current
       and previous line are the same */
    else if (!strncmp(buffer,prevline,10))
      printf(”Start the same:\n  %s  %s",prevline,buffer);
    /* Copy the current line (in buffer) to the previous
       line (in prevline) */
    strcpy(prevline,buffer);
  }

  fclose(instream);
}
