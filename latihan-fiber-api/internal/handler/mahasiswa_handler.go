package handler

import (
	"sort"
	"strconv"

	"latihan-fiber-api/internal/model"

	"github.com/gofiber/fiber/v3"
)

func DaftarMahasiswa(c fiber.Ctx) error {
	angkatan := c.Query("angkatan", "")
	var nilaiAngkatan int
	if angkatan != "" {
		var err error
		nilaiAngkatan, err = strconv.Atoi(angkatan)
		if err != nil {
			return fiber.NewError(fiber.StatusBadRequest, "Parameter angkatan harus berupa angka")
		}
	}

	hasil := make([]model.Mahasiswa, 0, len(model.Daftar))
	for _, mahasiswa := range model.Daftar {
		if angkatan == "" || mahasiswa.Angkatan == nilaiAngkatan {
			hasil = append(hasil, mahasiswa)
		}
	}

	switch c.Query("urut", "") {
	case "":
	case "nama":
		sort.SliceStable(hasil, func(i, j int) bool {
			return hasil[i].Nama < hasil[j].Nama
		})
	case "ipk":
		sort.SliceStable(hasil, func(i, j int) bool {
			return hasil[i].Ipk < hasil[j].Ipk
		})
	default:
		return fiber.NewError(fiber.StatusBadRequest, "Parameter urut harus bernilai nama atau ipk")
	}

	return c.JSON(fiber.Map{
		"sukses": true,
		"data":   hasil,
	})
}

func DetailMahasiswaNIM(c fiber.Ctx) error {
	nim := c.Params("nim")
	for _, mahasiswa := range model.Daftar {
		if mahasiswa.Nim == nim {
			return c.JSON(fiber.Map{
				"sukses": true,
				"data":   mahasiswa,
			})
		}
	}
	return fiber.NewError(fiber.StatusNotFound, "Mahasiswa tidak ditemukan")
}

func DetailMahasiswa(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka")
	}
	for i := 0; i < len(model.Daftar); i++ {
		if model.Daftar[i].ID == id {
			return c.JSON(fiber.Map{
				"sukses": true,
				"data":   model.Daftar[i],
			})
		}
	}
	return fiber.NewError(fiber.StatusNotFound, "Mahasiswa tidak ditemukan")
}
