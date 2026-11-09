package handler

import (
	"errors"
	"strconv"

	"latihan-fiber-api/internal/model"
	"latihan-fiber-api/internal/repository"

	"github.com/gofiber/fiber/v3"
	"gorm.io/gorm"
)

func DaftarMatakuliah(c fiber.Ctx) error {
	daftar, err := repository.AmbilSemuaMatakuliah()
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data mata kuliah")
	}
	return c.JSON(fiber.Map{
		"sukses": true,
		"data":   daftar,
	})
}

func DetailMatakuliah(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	matakuliah, err := repository.AmbilMatakuliahPerId(uint(id))
	if err != nil {
		if errors.Is(err, gorm.ErrRecordNotFound) {
			return fiber.NewError(fiber.StatusNotFound, "Mata kuliah tidak ditemukan")
		}
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data mata kuliah")
	}
	return c.JSON(fiber.Map{
		"sukses": true,
		"data":   matakuliah,
	})
}

func BuatMatakuliah(c fiber.Ctx) error {
	var permintaan PermintaanMatakuliah
	if err := c.Bind().Body(&permintaan); err != nil {
		return fiber.NewError(fiber.StatusBadRequest, "Format body tidak valid")
	}
	matakuliah := model.Matakuliah{
		ProgramStudiID: permintaan.ProgramStudiID,
		Kode:           permintaan.Kode,
		Nama:           permintaan.Nama,
		SKS:            permintaan.SKS,
		Semester:       permintaan.Semester,
	}
	if err := repository.SimpanMatakuliah(&matakuliah); err != nil {
		return fiber.NewError(fiber.StatusUnprocessableEntity, "Gagal menyimpan data mata kuliah")
	}
	return c.Status(fiber.StatusCreated).JSON(fiber.Map{
		"sukses": true,
		"pesan":  "Data mata kuliah berhasil dibuat",
		"data":   matakuliah,
	})
}

func PerbaruiMatakuliah(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	matakuliah, err := repository.AmbilMatakuliahPerId(uint(id))
	if err != nil {
		if errors.Is(err, gorm.ErrRecordNotFound) {
			return fiber.NewError(fiber.StatusNotFound, "Mata kuliah tidak ditemukan")
		}
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data mata kuliah")
	}
	var permintaan PermintaanMatakuliah
	if err := c.Bind().Body(&permintaan); err != nil {
		return fiber.NewError(fiber.StatusBadRequest, "Format body tidak valid")
	}
	if permintaan.ProgramStudiID > 0 {
		matakuliah.ProgramStudiID = permintaan.ProgramStudiID
	}
	if permintaan.Kode != "" {
		matakuliah.Kode = permintaan.Kode
	}
	if permintaan.Nama != "" {
		matakuliah.Nama = permintaan.Nama
	}
	if permintaan.SKS > 0 {
		matakuliah.SKS = permintaan.SKS
	}
	if permintaan.Semester > 0 {
		matakuliah.Semester = permintaan.Semester
	}
	if err := repository.PerbaruiMatakuliah(&matakuliah); err != nil {
		return fiber.NewError(fiber.StatusUnprocessableEntity, "Gagal memperbarui data mata kuliah")
	}
	return c.JSON(fiber.Map{
		"sukses": true,
		"pesan":  "Data mata kuliah berhasil diperbarui",
		"data":   matakuliah,
	})
}

func HapusMatakuliah(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	matakuliah, err := repository.AmbilMatakuliahPerId(uint(id))
	if err != nil {
		if errors.Is(err, gorm.ErrRecordNotFound) {
			return fiber.NewError(fiber.StatusNotFound, "Mata kuliah tidak ditemukan")
		}
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data mata kuliah")
	}
	if err := repository.HapusMatakuliah(&matakuliah); err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal menghapus data mata kuliah")
	}
	return c.JSON(fiber.Map{
		"sukses": true,
		"pesan":  "Data mata kuliah berhasil dihapus",
	})
}
