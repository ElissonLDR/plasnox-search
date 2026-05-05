# 🔍 Plasnox Search

Plugin WordPress de busca unificada desenvolvido para o site **[plasnox.com.br](https://plasnox.com.br)**.

---

## O que ele resolve

O WordPress nativo não pesquisava produtos WooCommerce nem categorias — apenas páginas e posts. O site da Plasnox também possui landing pages de soluções industriais (hierarquia *Soluções → Metais*) que precisavam aparecer na busca.

Este plugin unifica tudo em uma única caixa de pesquisa em tempo real.

---

## O que ele busca

| Tipo | Fonte |
|---|---|
| 🏷️ Produtos | WooCommerce (`post_type=product`) |
| 📂 Categorias | Taxonomia `product_cat` do WooCommerce |
| 📄 Soluções Industriais | Páginas filhas de *Metais* (`solucoes/metais`) |

---

## Stack e funções utilizadas

- **PHP** — classe singleton, hooks `wp_ajax`, `add_shortcode`
- **WP_Query / get_posts** — busca de produtos e páginas com `post__in` + `s`
- **get_terms** — busca de categorias por taxonomia
- **get_page_by_path / get_posts(post_parent)** — localiza a página Metais e seus filhos
- **wp_localize_script** — passa dados PHP → JavaScript
- **jQuery AJAX** — requisições ao endpoint com nonce e debounce de 300 ms
- **Elementor Widget API** — widget com controles de conteúdo e estilo (tipografia, cores, bordas, sombras, responsivo por breakpoint)

---

## Modos de exibição

- **Ícone** — exibe só a lupa; clicando, o campo expande para a esquerda
- **Sempre aberto** — campo sempre visível

Ambos configuráveis por dispositivo (desktop, tablet, mobile) direto no Elementor.

---

## Uso

**Widget Elementor:** pesquise por *Plasnox Search* no painel de widgets e arraste para a página.

**Shortcode:**
```
[plasnox_search]
```

---

## Requisitos

WordPress 6.0+ · PHP 7.4+ · WooCommerce 6.0+ · Elementor 3.0+ *(opcional)*

---

<p align="center">Desenvolvido para <a href="https://plasnox.com.br"><strong>plasnox.com.br</strong></a></p>
