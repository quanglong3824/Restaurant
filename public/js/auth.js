/**
 * auth.js — 3-Step Login Logic (User -> Shift -> PIN)
 * Aurora Restaurant
 */

"use strict";

(function () {
  // ── State ──────────────────────────────────────────────
  let pin = "";
  let selectedUsername = "";
  let selectedShift = "";

  // ── DOM refs ────────────────────────────────────────────
  const pinDots = document.querySelectorAll(".pin-dot");
  const pinField = document.getElementById("pinField");
  const usernameField = document.getElementById("usernameField");
  const shiftField = document.getElementById("shiftField");
  const submitBtn = document.getElementById("submitBtn");

  const waiterSection = document.getElementById("waiterSection");
  const shiftSection = document.getElementById("shiftSection");
  const pinSection = document.getElementById("pinSection");

  // ── Step Navigation ─────────────────────────────────────
  function checkSteps() {
    console.log('checkSteps called:', { selectedUsername, selectedShift, pinLength: pin.length });
    
    // Step 1 -> Step 2
    if (selectedUsername) {
      shiftSection.classList.remove("u-hidden");
      console.log('Shift section shown');
    } else {
      shiftSection.classList.add("u-hidden");
      pinSection.classList.add("u-hidden");
    }

    // Step 2 -> Step 3
    if (selectedUsername && selectedShift) {
      pinSection.classList.remove("u-hidden");
      console.log('PIN section shown');
    } else {
      pinSection.classList.add("u-hidden");
    }

    checkReady();
  }

  // ── PIN helpers ─────────────────────────────────────────
  function pressKey(value) {
    if (pin.length >= 4) return;
    pin += value;
    syncDots();
    if (pin.length === 4) {
      pinField.value = pin;
    }
    checkReady();
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
    const ready = pin.length === 4 &&
                  selectedUsername.trim().length > 0 &&
                  selectedShift.trim().length > 0;
    submitBtn.disabled = !ready;
    console.log('checkReady:', { ready, pinLength: pin.length, hasUsername: selectedUsername.trim().length > 0, hasShift: selectedShift.trim().length > 0 });
  }

  // ── Step 1: User selection ──────────────────────────────
  function selectUser(el) {
    document.querySelectorAll(".user-chip").forEach((c) => c.classList.remove("is-selected"));
    el.classList.add("is-selected");
    selectedUsername = el.dataset.username;
    usernameField.value = selectedUsername;
    console.log('User selected:', selectedUsername);

    // Reset following steps
    selectedShift = "";
    shiftField.value = "";
    document.querySelectorAll(".shift-chip").forEach((c) => c.classList.remove("is-selected"));
    pin = "";
    pinField.value = "";
    syncDots();

    checkSteps();
    
    // Scroll to shift section smoothly
    setTimeout(() => {
      shiftSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
  }

  // ── Step 2: Shift selection ─────────────────────────────
  function selectShift(el) {
    document.querySelectorAll(".shift-chip").forEach((c) => c.classList.remove("is-selected"));
    el.classList.add("is-selected");
    selectedShift = el.dataset.id;
    shiftField.value = selectedShift;
    console.log('Shift selected:', selectedShift);

    // Reset following steps
    pin = "";
    pinField.value = "";
    syncDots();

    checkSteps();
    
    // Scroll to PIN section smoothly
    setTimeout(() => {
      pinSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
  }

  // ── Bind Events ─────────────────────────────────────────
  document.querySelectorAll(".user-chip").forEach((chip) => {
    chip.addEventListener("click", () => selectUser(chip));
  });

  document.querySelectorAll(".shift-chip").forEach((chip) => {
    chip.addEventListener("click", () => selectShift(chip));
  });

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

  // ── Keyboard support (desktop) ──────────────────────────
  document.addEventListener("keydown", (e) => {
    if (pinSection.classList.contains("u-hidden")) return;
    if (e.key >= "0" && e.key <= "9") pressKey(e.key);
    if (e.key === "Backspace") deleteKey();
    if (e.key === "Enter" && !submitBtn.disabled) {
      document.getElementById("loginForm").submit();
    }
  });

  // Init
  console.log('auth.js initialized');
  checkSteps();
})();
