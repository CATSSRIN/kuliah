#include <stdio.h>
#include <string.h>

void main() {
  FILE *instream;
  FILE *outstream;
  char basefname[81];
  char readfname[101];
  char savefname[81];
  char buffer[101];
  int fnum;

  printf("File Prefix: ");
  scanf("%80s",basefname);
  printf("Save to File: ");
  scanf("%80s",savefname);

  if ((outstream = 
       fopen(savefname,"w")) ==
       NULL) {
    printf("Unable to open %s\n",
           savefname);
    exit(-1);
  }

 for (fnum = 0; fnum < 5; fnum++) {
    /* file name with basefname as prefix, fnum as suffix */
    sprintf(readfname,"%s.%d",basefname,fnum);

    if ((instream = fopen(readfname,"r")) == NULL) {
      printf("Unable to open input file %s\n",readfname);
      exit(-1);
    }

    while (fgets(buffer,sizeof(buffer)-1,instream) != NULL)
      fputs(buffer,outstream);

    fclose(instream);
  }

  fclose(outstream);
}
