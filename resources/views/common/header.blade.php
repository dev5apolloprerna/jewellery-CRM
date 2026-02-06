<?php 
if(auth()->user())
{
$roleid = auth()->user()->role_id;
}else{

$roleid = Auth::guard('web_employees')->user()->role_id;
}

?>

@php
    /**
     * ✅ IMPORTANT: Change these route names to your real routes
     * Example:
     *  $customerIndexUrl = route('customer-master.index');
     *  $productIndexUrl  = route('product-master.index');
     */
    $customerIndexUrl = route('customer.index');   // <-- CHANGE
    $productIndexUrl  = route('product.index');    // <-- CHANGE

    // Suggest API route
    $suggestUrl = route('global.search.suggest');  // you must create this route
@endphp

<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
           
            <div class="navbar-brand-box horizontal-logo">
                    <a href="{{ route('home') }}" class="logo logo-dark">
                        <span class="logo-lg">
                            <img src="{{ asset ('assets/images/logo.png')}}" alt="" height="70px">
                        </span>
                    </a>
                </div>    
                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

            </div>



            <div class="d-flex align-items-center">

                <div class="d-none d-md-block">
    <div class="position-relative">
        <input
            id="globalSearchInput"
            type="text"
            class="form-control"
            placeholder="Search customer / product (name, email, mobile...)"
            autocomplete="off"
            style="height:40px; border-radius:10px; padding-left:40px; padding-right:46px;"
        />

        <!-- left search icon -->
        <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); opacity:.6;">
            <i class="fa fa-search"></i>
        </span>

        <!-- ✅ reset button -->
        <button
            type="button"
            id="globalSearchReset"
            class="btn btn-light"
            title="Clear"
            style="
                position:absolute;
                right:8px;
                top:50%;
                transform:translateY(-50%);
                height:28px;
                width:28px;
                padding:0;
                border-radius:8px;
                border:1px solid #e9ecef;
                display:none;
                align-items:center;
                justify-content:center;
            ">
            <i class="fa fa-times" style="font-size:12px;"></i>
        </button>

        <div
            id="globalSearchDropdown"
            class="dropdown-menu show"
            style="
                display:none;
                width:100%;
                max-height:360px;
                overflow:auto;
                padding:0;
                margin-top:6px;
                border-radius:12px;
                border:1px solid #eee;
                box-shadow:0 10px 25px rgba(0,0,0,.08);
            "
        ></div>
    </div>

</div>


                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn shadow-none" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('assets/images/users/undraw_profile.webp') }}" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                    <?php
                                    ?>
                                   @if(auth()->user())
                                        {{ auth()->user()->full_name ?? Auth::guard('web_employees')->user()->emp_name  }}
                                    @else
                                        {{ Auth::guard('web_employees')->user()->emp_name }}
                                    @endif

                                </span>
                                <?php
                                if(auth()->user() && auth()->user()->id)
                                {
                                $session = auth()->user()->id;
                                $role = App\Models\User::select('users.id', 'roles.name')
                                    ->where('users.id', $session)
                                    ->join('roles', 'users.role_id', '=', 'roles.id')
                                    ->first();
                                ?>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                     {{ $role->name }}
                                 </span>
                                <?php
                                }else{

                                $session2 = Auth::guard('web_employees')->user()->emp_id;
                                $role2 = App\Models\Employee::select('employee_master.emp_id', 'roles.name')
                                ->where('employee_master.emp_id', $session2)
                                ->join('roles', 'employee_master.role_id', '=', 'roles.id')
                                ->first();
                                ?>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">
                                     {{ $role2->name }}
                                 </span>
                                <?php  }
                                ?>

                            </span>
                        </span>
                    </button>
                    <?php if($roleid == '1') 
                    { ?>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome {{ auth()->user()->full_name }}</h6>
                        <a class="dropdown-item" href="{{ route('profile.detail') }}"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a>
                        <a class="dropdown-item" href="{{ route('logout') }}"><i
                                class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                    <?php }else
                      { ?>
                      <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome {{ Auth::guard('web_employees')->user()->emp_name }}</h6>
                        <a class="dropdown-item" href="{{ route('empprofile.employee-detail') }}"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span>
                        </a>
                        <a class="dropdown-item" href="{{ route('empuserlogout') }}"><i
                                class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle" data-key="t-logout">Logout</span>
                        </a>
                        
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</header>


{{-- ✅ Global Search Script --}}
<script>
(function () {
  const inputDesktop = document.getElementById("globalSearchInput");
  const inputMobile  = document.getElementById("globalSearchInputMobile");
  const dropdown     = document.getElementById("globalSearchDropdown");

  const CUSTOMER_INDEX_URL = @json($customerIndexUrl);
  const PRODUCT_INDEX_URL  = @json($productIndexUrl);
  const SUGGEST_URL        = @json($suggestUrl);

  let t = null;

  function esc(s) {
    return String(s ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }

  function hideDropdown() {
    if (!dropdown) return;
    dropdown.style.display = "none";
    dropdown.innerHTML = "";
  }

  function showDropdown(html) {
    if (!dropdown) return;
    dropdown.innerHTML = html;
    dropdown.style.display = "block";
  }

  function buildItem(label, sub, url) {
    return `
      <a class="dropdown-item" href="${esc(url)}" style="padding:10px 12px; white-space:normal;">
        <div style="font-weight:700; font-size:13px;">${esc(label)}</div>
        ${sub ? `<div style="font-size:12px; opacity:.75; margin-top:2px;">${esc(sub)}</div>` : ``}
      </a>
    `;
  }

  async function fetchSuggest(q) {
    const res = await fetch(SUGGEST_URL + "?q=" + encodeURIComponent(q), {
      headers: { "X-Requested-With": "XMLHttpRequest" }
    });
    if (!res.ok) return { customers: [], products: [] };
    return await res.json();
  }

  async function runSearch(q) {
    clearTimeout(t);
    if (!q || q.trim().length < 2) {
      hideDropdown();
      return;
    }

    t = setTimeout(async () => {
      try {
        const data = await fetchSuggest(q.trim());
        const customers = Array.isArray(data.customers) ? data.customers : [];
        const products  = Array.isArray(data.products) ? data.products : [];

        let html = "";

        if (!customers.length && !products.length) {
          html = `<div style="padding:10px 12px; font-size:13px; opacity:.7;">No results</div>`;
          showDropdown(html);
          return;
        }

        if (customers.length) {
          html += `<div style="padding:8px 12px; font-size:12px; font-weight:800; opacity:.65; border-bottom:1px solid #f0f0f0;">CUSTOMERS</div>`;
          customers.forEach(c => {
            const label = c.customer_name || "Customer";
            const sub = [c.customer_phone, c.customer_email].filter(Boolean).join(" • ");
            // ✅ Requirement: customer search → go to Customer Master index page
            const url = CUSTOMER_INDEX_URL + "?search=" + encodeURIComponent(c.customer_name || q);
            html += buildItem(label, sub, url);
          });
        }

        if (products.length) {
          html += `<div style="padding:8px 12px; font-size:12px; font-weight:800; opacity:.65; border-top:1px solid #f0f0f0; border-bottom:1px solid #f0f0f0;">PRODUCTS</div>`;
          products.forEach(p => {
            const label = p.product_name || "Product";
            const sub = p.product_tag ? ("Tag: " + p.product_tag) : "";
            // ✅ Requirement: product search → go to Product page
            const url = PRODUCT_INDEX_URL + "?search=" + encodeURIComponent(p.product_name || q);
            html += buildItem(label, sub, url);
          });
        }

        showDropdown(html);
      } catch (e) {
        hideDropdown();
      }
    }, 250);
  }

  if (inputDesktop) {
    inputDesktop.addEventListener("input", () => runSearch(inputDesktop.value));
    inputDesktop.addEventListener("keydown", (e) => {
      if (e.key === "Escape") hideDropdown();
    });
  }

  if (inputMobile) {
    inputMobile.addEventListener("input", () => runSearch(inputMobile.value));
    inputMobile.addEventListener("keydown", (e) => {
      if (e.key === "Escape") hideDropdown();
    });
  }

  document.addEventListener("click", function (e) {
    if (!dropdown) return;
    const target = e.target;
    const isInside = dropdown.contains(target) || target === inputDesktop;
    if (!isInside) hideDropdown();
  });
})();
</script>
