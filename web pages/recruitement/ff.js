const sensors=[
    {id:"TEMP801",type:"temperature",value:22.5,status:"active"}
    {id:"TEMP802",type:"pollution",value:85.5,status:"active"}
    {id:"TEMP803",type:"humidity",value:12.5,status:"active"}
]
sensors.forEach(function(objet){
    console.log(objet.id," ",objet.status);
});
const result=sensors.filter()
//clé dynamique=valeure caculée : crochet obligatoire
//JSON ; un grand objet 
//stringify : stringify les attributs et non pas les méthodses
//js languages à bases de prototypes
//html !=js : html language à base de balises et texte---->navigateur 
//offre un outil de traducation 
//parser le code html
//associer à chaque balise un objet unique (équivalence) (contient les attrbuts de la balise et les attributs communes entre tus les objets)
//l'onglet courant---->object js
//fenetre courante --->object js
//Window--->document--->html: document objet global (regroupe les attributs)
//DOM : outile de traduction html---->objets