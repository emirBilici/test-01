!(async function($, $global) {
    "use strict";
    const post = async (uri, data) => {
        let res = await fetch(uri, {
            method: 'post',
            body: JSON.stringify(data),
            headers: {
                'accept': 'application/json',
                'content-type': 'application/json'
            }
        });
        return await res.json();
    }

    const response = await post('/tag', {
        submit: 1,
        tagId: $
    });
    if (response !== 'error') {
        const sidebar = document.getElementById('partial-index')
            , SIDEBAR_TAG_NAME = sidebar.querySelector('h2')
            , SIDEBAR_LIST = sidebar.querySelector('.topic-list');
        SIDEBAR_TAG_NAME.innerText = `#${response.name}`;
        if (response.posts.length !== 0) {
            SIDEBAR_LIST.innerHTML = response.posts.reduce((acc, post) => {
                if (post.post_title.length > 50) {
                    post.post_title = `${post.post_title.substring(0, 50)}...`;
                }
                let li = `<li><a href="/entry/${post.puID}">${post.post_title}<small>(0)</small></a></li>`;
                acc += li;
                return acc;
            }, '');
        } else {
            SIDEBAR_LIST.innerHTML = '<p>Burada gösterilecek bir şey yok.</p>';
        }

        const getTags = await fetch('/tags', {
            method: 'get',
            headers: {
                'accept': 'application/json'
            }
        }), jTagData = await getTags.json();

        const HeaderItems = (mobile = false) => {
            jTagData.forEach((elem, index, fetchDataArr) => {
                let count = index + 1;
                const list = document.querySelector('.sub-navigation.mobile-hidden .categories-list')
                    , mobileHeaderCategories = document.querySelector('.sub-navigation.mobile-show .categories-list');

                // no dropdown
                let li = document.createElement('li')
                    , a = document.createElement('a');

                const {link, name} = elem;
                a.href = link;
                a.innerText = name;
                if (Number(sessionStorage.getItem('selected_tag')) === count) {
                    li.classList.add('active');
                }
                li.appendChild(a);

                let FirstMobileCategory = mobileHeaderCategories.querySelector('[data-mobile-category="1"]')
                    , SecondMobileCategory = mobileHeaderCategories.querySelector('[data-mobile-category="2"]');

                if (mobile) {
                    if (count <= 2) {
                        if (count === 1) {
                            if(li.classList.contains('active'))
                                FirstMobileCategory.classList.add('active');
                            FirstMobileCategory.appendChild(a);
                        }
                        if (count === 2) {
                            SecondMobileCategory.appendChild(a);
                        }
                    } else {
                        let result = '';
                        const mobileCategoriesList = document.querySelector('[data-dropdown="mobile-header"]');
                        for (let i = 2; i < fetchDataArr.length; i++) {
                            result += `<li><a href="${fetchDataArr[i].link}">#${fetchDataArr[i].name}</a></li>`;
                        }
                        mobileCategoriesList.innerHTML = result;
                    }
                } else {
                    if (count <= 6) {
                        list.appendChild(li);
                    } else {
                        // add dropdown
                        let dropdownLi = document.createElement('li')
                            , threeDots = document.createElement('a')
                            , dropdownUl =document.createElement('ul');

                        dropdownLi.className = 'dropdown';
                        dropdownLi.dataset.openDropdown = 'desktop-header';
                        threeDots.href = '#';
                        threeDots.innerHTML = '<i aria-hidden="true" class="bi bi-three-dots"></i>';
                        dropdownLi.appendChild(threeDots);

                        dropdownUl.className = 'dropdown-list';
                        dropdownUl.id = 'desktop-header-dropdown';
                        dropdownUl.dataset.dropdown = 'desktop-header';
                        dropdownUl.appendChild(li);
                        dropdownLi.addEventListener('click', e => {
                            e.preventDefault();
                            dropdownUl.classList.toggle('open');
                        });
                        li.onclick = () => location.href = li.querySelector('a').href;
                        document.onclick = e => {
                            const path = e.composedPath();
                            if (!path.includes(dropdownLi) && dropdownUl.classList.contains('open'))
                                dropdownUl.classList.remove('open');
                        };

                        dropdownLi.appendChild(dropdownUl);
                        list.appendChild(dropdownLi);
                    }
                }
            });
        }

        const checkWindow = (width) => {
            if(width > 815) {
                HeaderItems();
            } else {
                HeaderItems(true);
            }
        }
        checkWindow($global.innerWidth);
    }

})(sessionStorage.getItem('selected_tag'), window)