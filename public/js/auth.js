/**
 * auth.js — PIN Login Logic
 * Aurora Restaurant
 */

"use strict";

(function () {
  // ── State ──────────────────────────────────────────────
  let pin = "";
  let selectedUsername = "";
  let isAdminMode = false;

  // ── DOM refs ────────────────────────────────────────────
  const pinDots = document.querySelectorAll(".pin-dot");
  const pinField = document.getElementById("pinField");
  const usernameField = document.getElementById("usernameField");
  const submitBtn = document.getElementById("submitBtn");
  const adminSection = document.getElementById("adminSection");
  const waiterSection = document.getElementById("waiterSection");
  const adminUsernameInput = document.getElementById("adminUsername");
  const adminToggleText = document.getElementById("adminToggleText");

  // ── PIN helpers ─────────────────────────────────────────
  function pressKey(value) {
    if (pin.length >= 4) return;
    pin += value;
    syncDots();
    if (pin.length === 4) {
      pinField.value = pin;
      checkReady();
    }
  }

  function deleteKey() {
    if (pin.length === 0) return;
    pin = pin.slice(0, -1);
    pinField.value = "";
    syncDots();
    checkReady();
  }

  function syncDots() {
    pinDots.forEach((dot, i) => {
      dot.classList.toggle("is-filled", i < pin.length);
    });
  }

  function checkReady() {
    const ready = pin.length === 4 && selectedUsername.trim().length > 0;
    submitBtn.disabled = !ready;
  }

  // ── User selection ──────────────────────────────────────
  function selectUser(el) {
    document
      .querySelectorAll(".user-chip")
      .forEach((c) => c.classList.remove("is-selected"));
    el.classList.add("is-selected");
    selectedUsername = el.dataset.username;
    usernameField.value = selectedUsername;
    checkReady();
  }

  // ── Admin toggle ────────────────────────────────────────
  function toggleAdmin() {
    isAdminMode = !isAdminMode;

    if (isAdminMode) {
      adminSection.classList.remove("u-hidden");
      waiterSection.classList.add("u-hidden");
      adminToggleText.textContent = "← Quay lại Phục vụ";
      selectedUsername = "";
      usernameField.value = "";
      document
        .querySelectorAll(".user-chip")
        .forEach((c) => c.classList.remove("is-selected"));
    } else {
      adminSection.classList.add("u-hidden");
      waiterSection.classList.remove("u-hidden");
      adminToggleText.textContent = "Đăng nhập Admin / IT";
      if (adminUsernameInput) adminUsernameInput.value = "";
      selectedUsername = "";
      usernameField.value = "";
    }

    // Reset PIN when switching mode
    pin = "";
    pinField.value = "";
    syncDots();
    checkReady();
  }

  // ── Bind PIN pad keys ───────────────────────────────────
  document.querySelectorAll(".pin-key[data-key]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const val = btn.dataset.key;
      if (val === "del") {
        deleteKey();
      } else {
        pressKey(val);
      }
    });
  });

  // ── Bind user chips ──────────────────────────────────────
  document.querySelectorAll(".user-chip").forEach((chip) => {
    chip.addEventListener("click", () => selectUser(chip));
  });

  // ── Admin username input ────────────────────────────────
  if (adminUsernameInput) {
    adminUsernameInput.addEventListener("input", () => {
      selectedUsername = adminUsernameInput.value.trim();
      usernameField.value = selectedUsername;
      checkReady();
    });
  }

  // ── Admin toggle click ──────────────────────────────────
  document
    .querySelector(".admin-toggle")
    ?.addEventListener("click", toggleAdmin);

  // ── Keyboard support (desktop) ──────────────────────────
  document.addEventListener("keydown", (e) => {
    if (document.activeElement === adminUsernameInput) return;
    if (e.key >= "0" && e.key <= "9") pressKey(e.key);
    if (e.key === "Backspace") deleteKey();
    if (e.key === "Enter" && !submitBtn.disabled) {
      document.getElementById("loginForm").submit();
    }
  });

  // ── Form submit guard ────────────────────────────────────
  document.getElementById("loginForm").addEventListener("submit", function () {
    pinField.value = pin;
    if (isAdminMode && adminUsernameInput) {
      usernameField.value = adminUsernameInput.value.trim();
    }
  });

  // Init
  checkReady();
})();
