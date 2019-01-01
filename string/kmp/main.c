#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#define MAX_LEN 100

int * getnext(const char * pattern);

int main()
{
        char string[MAX_LEN];
        char pattern[MAX_LEN];

        int *next;

        scanf("%s", string);
        scanf("%s", pattern);

        int slen = strlen(string);
        int plen = strlen(pattern);

        if(plen > slen || slen > MAX_LEN || plen > MAX_LEN)
        {
                exit(0);
        }

        next = getnext(string);

        int q = -1;

        for(int i = 0; i < slen; i++)
        {
                while(q >= 0 && pattern[q+1] != string[i])
                {
                        q = next[q];

                }
                if(pattern[q+1] == string[i])
                {
                        q++;
                }
                if(q == plen - 1)
                {
                        printf("pattern occurs with shift %d\n", i + 1 - plen);
                        q = next[q];
                }
        }

        return 1;
}

int * getnext(const char * pattern)
{
        int plen = strlen(pattern);

        int *next = (int *)malloc(MAX_LEN * sizeof(int));

        next[0] = -1;

        int k = -1;

        for(int j = 1; j < plen; ++j)
        {

                while(k >= 0 && pattern[j] != pattern[k+1])
                {
                        k = next[k];
                }

                if(pattern[k+1] == pattern[j])
                {
                        k++;
                }

                next[j] = k;
        }

        return next;

        //for(int i = 0; i < plen; i++)
        //{
        //        printf("%d ", next[i] + 1);
        //} 
}
