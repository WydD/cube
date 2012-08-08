# Projet C 2010, ENSIMAG 1A, sujet simulateur MIPS
# ------------------------------------------------

# variables qui dependent de la machine (consulter les commentaires de ce fichier !)
SHELL=/bin/bash
CC = gcc

CFLAGS = -m32 -Wall -Wextra -std=c99 -O3 
LINK   = $(CC) -m32

# les executables produits
EXE = cubegen

# variable qui contient les ".c" du simulateur et des programmes tests
SRC_EXE =  # aucun pour l'instant, le module simips_base.o contient un main
# fichiers sources simulateur
SRCS = cube.c main.c searcher.c

SRC_ET = $(SRCS:%=src/%)
# tous les sources (pour le clean)
SRC_ALL = $(SRC_EXE:%=src/%) $(SRC_ET)

# les binaires compiles
OBJ_EXE  = $(SRC_EXE:src/%.c=obj/%.o)
OBJ_ET   = $(SRC_ET:src/%.c=obj/%.o)
OBJ_ALL  = $(OBJ_ET) $(OBJ_EXE)

# les binaires distribues
OBJ_DISTRIB = 
GCCOPT=-Iinclude/ -Isrc/

# les cibles qui ne sont pas des fichiers
.PHONY: all clean allclean

# compilation gale
all : $(EXE) $(DEPC_ALL)

# Nettoyage:
clean :
	/bin/rm -f $(OBJ_ALL) core* *~

allclean:
	/bin/rm -f *~ $(EXE) $(OBJ_ALL) 
	/bin/rm -f -r Doc

obj/%.o: src/%.c
	gcc -c $(GCCOPT) $(CFLAGS) $< -o $@

# Edition de liens / Executables :
cubegen : $(OBJ_DISTRIB) $(OBJ_ET)
	$(LINK) $^ -o $@


# Documentation html
doc:
	doxygen doxyfile.cfg
	
