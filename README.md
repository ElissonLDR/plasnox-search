# 🔍 Plasnox Search

> Plugin WordPress de busca unificada desenvolvido para o site da **[Plasnox](https://plasnox.com.br)** — indústria brasileira de equipamentos de movimentação e linha de metais industriais.

---

## 💡 O Problema que ele Resolve

O site da Plasnox possui **três tipos diferentes de conteúdo pesquisável** que a busca nativa do WordPress não cobria de forma unificada:

| # | Tipo de conteúdo | Exemplos |
|---|---|---|
| 1 | **Produtos WooCommerce** | Empilhadeiras, paleteiras, plataformas, rampas e acessórios |
| 2 | **Categorias de produto** | Diesel, Elétrica, GLP, Conexões Industriais, Válvulas… |
| 3 | **Páginas da Linha Industrial** | Landing pages de soluções filhas da hierarquia *Soluções → Metais* |

A busca nativa do WordPress retornava apenas posts e páginas genéricas. Os produtos WooCommerce e categorias ficavam de fora, e as landing pages industriais não tinham nenhum mecanismo de descoberta rápida.

---

## ✨ Funcionalidades

- 🔎 **Busca unificada** — produtos, categorias e páginas industriais em uma única consulta AJAX
- ⚡ **Busca em tempo real** com debounce de 300 ms — pesquisa enquanto digita sem sobrecarregar o servidor
- 🎛️ **Widget nativo do Elementor** — arraste, solte e configure visualmente, sem escrever código
- 📱 **Responsivo por dispositivo** — modo e largura configuráveis separadamente para Desktop, Tablet e Mobile
- 🎨 **Totalmente estilizável** — controles de cor, tipografia, borda, sombra e espaçamento direto no painel do Elementor
- 🔁 **Dois modos de exibição** — *Ícone (expandir ao clicar)* ou *Sempre aberto*
- ⌨️ **Navegação por teclado** — setas ↑ ↓, Enter e Escape funcionam no dropdown de resultados
- 🔒 **Seguro** — nonce AJAX em todas as requisições e sanitização de entradas
- 🧱 **Múltiplas instâncias** — pode ser usado no cabeçalho, rodapé e páginas ao mesmo tempo

---

## 🖥️ Como Funciona Visualmente

```
Fechado:   [ 🔍 ]
Aberto:    [ ___________________________ ][ 🔍 ]

Resultado:
┌──────────────────────────────────┐
│ PRODUTOS                         │
│  🖼  Empilhadeira Diesel 2.5t    │
│  🖼  Empilhadeira Elétrica 3.5t  │
│ CATEGORIAS                       │
│  🖼  Diesel  · 10 produtos       │
│  🖼  GLP     ·  4 produtos       │
│ SOLUÇÕES INDUSTRIAIS             │
│  🖼  Válvulas                    │
│  🖼  Conexões                    │
└──────────────────────────────────┘
```

---

## 📦 Instalação

### Via FTP ou Gerenciador de Arquivos
1. Faça upload da pasta `plasnox-search/` para `wp-content/plugins/`
2. Acesse **Painel WP → Plugins → Plugins Instalados**
3. Ative o **Plasnox Search**

### Via Painel do WordPress
1. Compacte a pasta `plasnox-search/` em um arquivo `.zip`
2. Acesse **Painel WP → Plugins → Adicionar Novo → Enviar Plugin**
3. Selecione o `.zip` e clique em **Instalar Agora**
4. Ative o plugin

---

## 🚀 Como Usar

### Widget do Elementor *(recomendado)*
1. Abra qualquer página no **Elementor**
2. No painel de widgets, pesquise por **"Plasnox Search"**
3. Arraste o widget para a posição desejada (cabeçalho, hero, barra lateral…)
4. Configure as opções nas abas **Conteúdo** e **Estilo**

### Shortcode
Insira em qualquer página, post ou widget de texto:
```
[plasnox_search]
```
> ⚠️ Dentro do Elementor, utilize o widget dedicado **Shortcode** para inserir o código acima.

---

## 🎨 Controles do Widget Elementor

<details>
<summary><strong>📐 Conteúdo — Configurações</strong></summary>

| Controle | Descrição |
|---|---|
| Modo de exibição | `Ícone (expandir)` ou `Sempre aberto` — configurável por Desktop, Tablet e Mobile |
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
| Borda | Cor e espessura |
| Raio da borda | Independente por canto |
| Sombra | Box shadow normal e hover |

</details>

<details>
<summary><strong>📝 Estilo — Campo de Busca</strong></summary>

| Controle | Descrição |
|---|---|
| Tipografia | Família, tamanho, peso e demais opções |
| Cor do texto | Cor do que é digitado |
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
| Cor da borda | Borda do dropdown |
| Raio da borda | Arredondamento dos cantos |
| Sombra | Box shadow |
| Altura máxima | Limite de altura com rolagem |

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
| Cor da informação extra | SKU do produto ou contagem de itens da categoria |
| Tipografia da informação extra | |
| Padding do card | Espaçamento interno |
| Tamanho da imagem | Largura e altura da miniatura |
| Raio da imagem | Arredondamento da miniatura |

</details>

---

## 🗂️ Estrutura de Arquivos

```
plasnox-search/
├── plasnox-search.php          # Inicialização do plugin, AJAX e registro do widget
├── includes/
│   └── widget-elementor.php   # Widget Elementor completo com todos os controles
└── assets/
    ├── plasnox-search.css      # Estilos base (sobrepostos pelo Elementor por widget)
    └── plasnox-search.js       # Busca em tempo real, toggle, múltiplas instâncias e responsivo
```

---

## ⚙️ Requisitos

| Requisito | Versão mínima |
|---|---|
| WordPress | 6.0+ |
| PHP | 7.4+ |
| WooCommerce | 6.0+ |
| Elementor | 3.0+ *(opcional, apenas para o widget)* |

---

## 🔧 Detalhes Técnicos

- **Endpoint AJAX:** `wp_ajax_plasnox_search` + `wp_ajax_nopriv_plasnox_search`
- **Segurança:** nonce `pls_nonce` renovado a cada carregamento de página
- **Busca de produtos:** `WP_Query` com `post_type=product` e parâmetro `s`
- **Busca de categorias:** `get_terms` com `taxonomy=product_cat` e parâmetro `search`
- **Busca de soluções:** localiza a página *Metais* via `get_page_by_path('solucoes/metais')`, coleta todos os filhos diretos com `get_posts(post_parent=metais_id)` e filtra por título usando `mb_stripos`
- **Múltiplas instâncias:** cada `.pls-wrapper` é inicializado como objeto independente `PlsInstance`
- **Responsivo via JS:** lê os atributos `data-mode-d`, `data-mode-t` e `data-mode-m` do wrapper e aplica o modo correto conforme `window.innerWidth`

---

## 🌐 Sobre a Plasnox

Plugin desenvolvido para o site **[plasnox.com.br](https://plasnox.com.br)** — empresa industrial brasileira fundada em 1987, especializada em:

- 🏗️ **Equipamentos de Movimentação** — empilhadeiras elétricas, GLP e diesel (1,5t a 16t), paleteiras, plataformas e rampas
- 🔩 **Linha Industrial de Metais** — válvulas (esfera, gaveta, borboleta, globo, sanitária e forjada), conexões, tubos, flanges e acessórios

Atende segmentos como mineração, óleo e gás, alimentício, automotivo, siderurgia e agronegócio.

---

## 👨‍💻 Autor

**Elisson Rodrigues** — [V4 Company](https://v4company.com)

---

<p align="center">
  Desenvolvido com ❤️ para <a href="https://plasnox.com.br"><strong>plasnox.com.br</strong></a>
</p>
