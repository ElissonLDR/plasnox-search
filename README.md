# 🔍 Plasnox Search

> Plugin WordPress de busca unificada desenvolvido para o site da **[Plasnox](https://plasnox.com.br)** — indústria brasileira de equipamentos de movimentação e linha de metais industriais.

---

## 💡 O Problema que ele Resolve

O site da Plasnox possui **três tipos diferentes de conteúdo pesquisável** que a busca nativa do WordPress não cobria de forma unificada:

| # | Tipo de conteúdo | Onde fica |
|---|---|---|
| 1 | **Produtos WooCommerce** | Empilhadeiras, paleteiras, plataformas, rampas e acessórios |
| 2 | **Categorias de produto** | Diesel, Elétrica, GLP, Conexões Industriais, Válvulas… |
| 3 | **Páginas da Linha Industrial** | Landing pages de soluções filhas da hierarquia *Soluções → Metais* |

A busca nativa do WordPress retornava apenas posts/páginas genéricas. Os produtos WooCommerce e as categorias ficavam de fora, e as landing pages industriais não tinham nenhum mecanismo de descoberta rápida.

---

## ✨ Funcionalidades

- 🔎 **Busca unificada** — produtos, categorias e páginas industriais em uma única consulta AJAX
- ⚡ **Live search** com debounce de 300 ms — pesquisa enquanto digita sem sobrecarregar o servidor
- 🎛️ **Widget nativo do Elementor** — arraste, solte e configure visualmente, sem código
- 📱 **Responsivo por dispositivo** — modo e largura configuráveis separadamente para Desktop, Tablet e Mobile
- 🎨 **Totalmente estilizável** — controles de cor, tipografia, borda, sombra e espaçamento direto no Elementor
- 🔁 **Dois modos de exibição** — *Ícone (expandir ao clicar)* ou *Sempre aberto*
- ⌨️ **Navegação por teclado** — ↑ ↓ Enter Escape funcionam no dropdown
- 🔒 **Seguro** — nonce AJAX em todas as requisições, sanitização de inputs
- 🧱 **Compatível com múltiplas instâncias** — pode ser usado em header, footer e páginas ao mesmo tempo

---

## 🖥️ Demonstração de Uso

```
Fechado:   [ 🔍 ]
Aberto:    [ ___________________________ ][ 🔍 ]

Resultado:
┌─────────────────────────────────┐
│ PRODUTOS                        │
│  🖼  Empilhadeira Diesel 2.5t   │
│  🖼  Empilhadeira Elétrica 3.5t │
│ CATEGORIAS                      │
│  🖼  Diesel  · 10 produtos      │
│  🖼  GLP     ·  4 produtos      │
│ SOLUÇÕES INDUSTRIAIS            │
│  🖼  Válvulas                   │
│  🖼  Conexões                   │
└─────────────────────────────────┘
```

---

## 📦 Instalação

### Via FTP / File Manager
1. Faça upload da pasta `plasnox-search/` para `wp-content/plugins/`
2. Acesse **WP Admin → Plugins → Plugins Instalados**
3. Ative o **Plasnox Search**

### Via WordPress Admin
1. Compacte a pasta `plasnox-search/` em um arquivo `.zip`
2. Acesse **WP Admin → Plugins → Adicionar Novo → Enviar Plugin**
3. Selecione o `.zip` e clique em **Instalar Agora**
4. Ative o plugin

---

## 🚀 Como Usar

### Widget do Elementor *(recomendado)*
1. Abra qualquer página no **Elementor**
2. No painel de widgets, pesquise **"Plasnox Search"**
3. Arraste o widget para a posição desejada (header, hero, sidebar…)
4. Configure nos painéis **Conteúdo** e **Estilo**

### Shortcode
Insira em qualquer página, post ou widget de texto:
```
[plasnox_search]
```
> ⚠️ No Elementor, use o widget dedicado **Shortcode** para inserir o código acima.

---

## 🎨 Controles do Widget Elementor

<details>
<summary><strong>📐 Conteúdo — Configurações</strong></summary>

| Controle | Descrição |
|---|---|
| Modo de exibição | `Ícone (expandir)` ou `Sempre aberto` — configurável por Desktop / Tablet / Mobile |
| Largura do campo | Largura em px do input expandido — responsivo |
| Placeholder | Texto exibido quando o campo está vazio |

</details>

<details>
<summary><strong>🔘 Estilo — Botão / Ícone</strong></summary>

| Controle | Descrição |
|---|---|
| Tamanho do botão | Largura e altura do box do ícone |
| Tamanho do ícone | Tamanho do SVG da lupa |
| Cor do ícone | Normal e Hover |
| Cor de fundo | Normal e Hover |
| Borda | Cor, espessura (Group Control) |
| Raio da borda | Independente por canto |
| Sombra | Box shadow normal e hover |

</details>

<details>
<summary><strong>📝 Estilo — Campo de Busca</strong></summary>

| Controle | Descrição |
|---|---|
| Tipografia | Família, tamanho, peso, etc. |
| Cor do texto | Cor digitada |
| Cor do placeholder | Cor do texto de dica |
| Cor de fundo | Background do input |
| Borda | Cor e espessura |
| Raio da borda | Independente por canto |
| Altura | Altura do campo |
| Padding | Espaçamento interno |

</details>

<details>
<summary><strong>📋 Estilo — Dropdown de Resultados</strong></summary>

| Controle | Descrição |
|---|---|
| Cor de fundo | Background do dropdown |
| Cor da borda | Border do dropdown |
| Raio da borda | Arredondamento |
| Sombra | Box shadow |
| Altura máxima | Max-height com scroll |

</details>

<details>
<summary><strong>🏷️ Estilo — Rótulos de Grupo</strong></summary>

Tipografia, cor do texto, cor de fundo e padding dos cabeçalhos de seção (PRODUTOS / CATEGORIAS / SOLUÇÕES INDUSTRIAIS).

</details>

<details>
<summary><strong>🃏 Estilo — Cards de Resultado</strong></summary>

| Controle | Descrição |
|---|---|
| Cor de fundo | Normal e Hover |
| Cor do título | Normal e Hover |
| Cor da seta | Normal e Hover |
| Tipografia do título | Família, tamanho, peso… |
| Cor da meta | SKU / contagem de produtos |
| Tipografia da meta | |
| Padding do card | |
| Tamanho da imagem | Largura e altura do thumbnail |
| Raio da imagem | Arredondamento do thumbnail |

</details>

---

## 🗂️ Estrutura de Arquivos

```
plasnox-search/
├── plasnox-search.php          # Bootstrap do plugin + registro do widget
├── includes/
│   └── widget-elementor.php   # Widget Elementor completo (controles + render)
└── assets/
    ├── plasnox-search.css      # Estilos base (variáveis sobrepostas pelo Elementor)
    └── plasnox-search.js       # Live search, toggle, múltiplas instâncias, responsive
```

---

## ⚙️ Requisitos

| Requisito | Versão mínima |
|---|---|
| WordPress | 6.0+ |
| PHP | 7.4+ |
| WooCommerce | 6.0+ |
| Elementor | 3.0+ *(opcional, para o widget)* |

---

## 🔧 Detalhes Técnicos

- **AJAX endpoint:** `wp_ajax_plasnox_search` + `wp_ajax_nopriv_plasnox_search`
- **Nonce:** `pls_nonce` renovado em cada carregamento de página
- **Busca de produtos:** `WP_Query` com `post_type=product`, `s=query`
- **Busca de categorias:** `get_terms` com `taxonomy=product_cat`, `search=query`
- **Busca de soluções:** localiza a página *Metais* via `get_page_by_path('solucoes/metais')`, coleta todos os filhos diretos via `get_posts(post_parent=metais_id)`, filtra por título com `mb_stripos`
- **Múltiplas instâncias:** cada `.pls-wrapper` é inicializado como objeto independente `PlsInstance`
- **Responsive JS:** lê `data-mode-d/t/m` do wrapper e aplica o modo correto conforme `window.innerWidth`

---

## 🌐 Sobre a Plasnox

O plugin foi desenvolvido para o site **[plasnox.com.br](https://plasnox.com.br)** — empresa industrial brasileira fundada em 1987, especializada em:

- 🏗️ **Equipamentos de Movimentação** — empilhadeiras elétricas, GLP e diesel (1,5t a 16t), paleteiras, plataformas e rampas
- 🔩 **Linha Industrial de Metais** — válvulas (esfera, gaveta, borboleta, globo, sanitária, forjada), conexões, tubos, flanges e acessórios

Atende segmentos como mineração, óleo e gás, alimentício, automotivo, siderurgia e agronegócio.

---

## 👨‍💻 Autor

**Elisson Rodrigues** — [V4 Company](https://v4company.com)

---

<p align="center">
  Desenvolvido com ❤️ para <a href="https://plasnox.com.br"><strong>plasnox.com.br</strong></a>
</p>
